<?php

namespace App\Http\Controllers;

use App\Models\StatementItems;
use App\Models\Statements as StatementsModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class StatementsController extends Controller
{
    private const UPLOAD_DISK   = 'local';
    private const UPLOAD_FOLDER = 'statements';

    // ----------------------------------------------------------- //
    public function index()
    {
        return view('commissions.statements', [
            'statements' => StatementsModel::loadStatements()
        ]);
    }

    // ----------------------------------------------------------- //
    public function loadStatementsList()
    {
        return view('commissions.statementsList', [
            'statements' => StatementsModel::loadStatements()
        ]);
    }

    // ----------------------------------------------------------- //
    public function saveStatement(Request $request)
    {
        $statementName = trim((string)$request->input('statementName'));
        $rows = json_decode((string)$request->input('statementRows'), true);

        if (!is_array($rows)) {
            return $this->statementsJson(0, "Invalid Table Data, Please Scan The File Again.");
        }

        $items = $this->sanitizeStatementRows($rows);

        if (count($items) == 0) {
            return $this->statementsJson(0, "Please Add At Least One Row Before Saving.");
        }

        $file = $request->file('statementFile');

        if (!$file || (string)$file->getClientOriginalName() === '') {
            return $this->statementsJson(0, "Please Select A PDF Or TXT File.");
        }

        $extension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));

        if (!in_array($extension, ['pdf', 'txt'])) {
            return $this->statementsJson(0, "Only PDF And TXT Files Are Allowed.");
        }

        $uploadDir = $this->getStatementsUploadDir();

        if ($uploadDir === false) {
            return $this->statementsJson(0, "The Upload Folder Is Not Writable, Please Contact Support.");
        }

        if (!$file->isValid()) {
            return $this->statementsJson(0, $file->getErrorMessage());
        }

        $validator = Validator::make($request->all(), [
            'statementFile' => 'file|mimes:pdf,txt|max:20480'
        ], [
            'statementFile.mimes' => 'The filetype you are attempting to upload is not allowed.',
            'statementFile.max'   => 'The file you are attempting to upload is larger than the permitted size.'
        ]);

        if ($validator->fails()) {
            return $this->statementsJson(0, $validator->errors()->first('statementFile'));
        }

        $storedFileName = md5(uniqid((string)mt_rand(), true)) . '.' . $extension;
        $storedPath = $file->storeAs(self::UPLOAD_FOLDER, $storedFileName, self::UPLOAD_DISK);

        if ($storedPath === false) {
            return $this->statementsJson(0, "A problem was encountered while attempting to move the uploaded file to the final destination.");
        }

        $originalFileName = $this->cleanStatementValue($file->getClientOriginalName(), 255);

        if ($statementName === '') {
            $statementName = pathinfo($originalFileName, PATHINFO_FILENAME);
        }

        $statementName = $this->cleanStatementValue($statementName, 255);

        $totalCommission = 0;
        foreach ($items as $item) {
            if ($item['agency_commission'] !== null) {
                $totalCommission += (float)$item['agency_commission'];
            }
        }

        $statementData = [
            'statement_name'     => $statementName,
            'original_file_name' => $originalFileName,
            'stored_file_name'   => $storedFileName,
            'file_size'          => (int)$file->getSize(),
            'total_commission'   => number_format($totalCommission, 2, '.', ''),
            'uploaded_by'        => $this->getStatementsUserId(),
            'is_deleted'         => 0
        ];

        $statementId = StatementsModel::insertStatement($statementData, $items);

        if ($statementId <= 0) {
            Storage::disk(self::UPLOAD_DISK)->delete($storedPath);
            return $this->statementsJson(0, "Failed To Save Statement, Please Try Again.");
        }

        return $this->statementsJson(1, "Statement Saved Successfully (" . count($items) . " Rows).", [
            'statementId' => $statementId
        ]);
    }

    // ----------------------------------------------------------- //
    public function viewStatementFile($statementId = 0)
    {
        $statement = StatementsModel::getStatementById((int)$statementId);

        if (!is_object($statement)) {
            abort(404);
        }

        $uploadDir = $this->getStatementsUploadDir();

        if ($uploadDir === false) {
            abort(404);
        }

        $filePath = $uploadDir . basename($statement->stored_file_name);

        if (!is_file($filePath)) {
            abort(404);
        }

        $downloadName = preg_replace('/[^A-Za-z0-9._ -]/', '_', $statement->original_file_name);
        $extension = strtolower(pathinfo($statement->stored_file_name, PATHINFO_EXTENSION));
        $contentType = $extension === 'txt' ? 'text/plain; charset=utf-8' : 'application/pdf';

        return response(file_get_contents($filePath), 200, [
            'Content-Type'           => $contentType,
            'Content-Disposition'    => 'inline; filename="' . $downloadName . '"',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }

    // ----------------------------------------------------------- //
    public function getStatementItems(Request $request)
    {
        $statementId = (int)$request->input('statementId');

        $statement = StatementsModel::getStatementById($statementId);

        if (!is_object($statement)) {
            return $this->statementsJson(0, "Statement Not Found.");
        }

        $items = [];

        foreach (StatementItems::getStatementItems($statementId) as $item) {
            $items[] = [
                'id'                => (int)$item->id,
                'reservationNumber' => (string)$item->reservation_number,
                'clientName'        => (string)$item->client_name,
                'groupNumber'       => (string)$item->group_number,
                'agencyCommission'  => $item->agency_commission !== null ? number_format((float)$item->agency_commission, 2) : '',
                'checkoutDate'      => $item->checkout_date ? date('m/d/Y', strtotime($item->checkout_date)) : '',
                'hotelName'         => (string)$item->hotel_name,
                'reservationId'     => $item->reservation_id !== null ? (int)$item->reservation_id : null
            ];
        }

        return $this->statementsJson(1, '', [
            'statement' => [
                'id'               => (int)$statement->id,
                'name'             => $statement->statement_name,
                'originalFileName' => $statement->original_file_name,
                'fileUrl'          => route('statements.file', (int)$statement->id),
                'createdAt'        => date('m/d/Y g:i A', strtotime($statement->created_at)),
                'uploadedBy'       => trim((string)$statement->uploadedByName),
                'totalCommission'  => number_format((float)$statement->total_commission, 2)
            ],
            'items' => $items
        ]);
    }

    // ----------------------------------------------------------- //
    public function deleteStatement(Request $request)
    {
        $statementId = (int)$request->input('statementId');

        if ($statementId <= 0) {
            return $this->statementsJson(0, "Invalid Statement.");
        }

        $result = StatementsModel::deleteStatement($statementId);

        if ($result != 1) {
            return $this->statementsJson(0, "Failed To Delete Statement, Please Try Again.");
        }

        return $this->statementsJson(1, "Statement Deleted Successfully.");
    }

    // ----------------------------------------------------------- //
    private function statementsJson($status, $message, $extra = [])
    {
        return response()->json(array_merge(['status' => $status, 'message' => $message], $extra));
    }

    // ----------------------------------------------------------- //
    private function getStatementsUploadDir()
    {
        $disk = Storage::disk(self::UPLOAD_DISK);

        try {
            if (!$disk->exists(self::UPLOAD_FOLDER)) {
                $disk->makeDirectory(self::UPLOAD_FOLDER);
            }
        } catch (Throwable $e) {
            report($e);
            return false;
        }

        $path = rtrim($disk->path(self::UPLOAD_FOLDER), '/\\') . DIRECTORY_SEPARATOR;

        return (is_dir($path) && is_writable($path)) ? $path : false;
    }

    // ----------------------------------------------------------- //
    private function getStatementsUserId()
    {
        $userId = Auth::id();

        return $userId ? (int)$userId : null;
    }

    // ----------------------------------------------------------- //
    private function sanitizeStatementRows($rows)
    {
        $items = [];
        $sortOrder = 0;
        $now = now()->format('Y-m-d H:i:s');

        foreach ($rows as $row) {
            if (!is_array($row)) continue;

            $reservationNumber = $this->cleanStatementValue($row['reservationNumber'] ?? '', 100);
            $clientName        = $this->cleanStatementValue($row['clientName'] ?? '', 255);
            $groupNumber       = $this->cleanStatementValue($row['groupNumber'] ?? '', 100);
            $hotelName         = $this->cleanStatementValue($row['hotelName'] ?? '', 255);
            $agencyCommission  = $this->parseStatementAmount($row['agencyCommission'] ?? '');
            $checkoutDate      = $this->parseStatementDate($row['checkoutDate'] ?? '');

            if ($reservationNumber === '' && $clientName === '' && $groupNumber === '' && $hotelName === '' && $agencyCommission === null && $checkoutDate === null) {
                continue;
            }

            $items[] = [
                'reservation_number' => $reservationNumber !== '' ? $reservationNumber : null,
                'client_name'        => $clientName !== '' ? $clientName : null,
                'group_number'       => $groupNumber !== '' ? $groupNumber : null,
                'agency_commission'  => $agencyCommission,
                'checkout_date'      => $checkoutDate,
                'hotel_name'         => $hotelName !== '' ? $hotelName : null,
                'reservation_id'     => null,
                'sort_order'         => $sortOrder++,
                'created_at'         => $now,
                'updated_at'         => $now,
                'is_deleted'         => 0
            ];
        }

        return $items;
    }

    // ----------------------------------------------------------- //
    private function cleanStatementValue($value, $maxLength)
    {
        $value = trim(preg_replace('/\s+/u', ' ', (string)$value));

        return function_exists('mb_substr') ? mb_substr($value, 0, $maxLength, 'UTF-8') : substr($value, 0, $maxLength);
    }

    // ----------------------------------------------------------- //
    private function parseStatementAmount($value)
    {
        $value = trim((string)$value);

        if ($value === '') return null;

        $isNegative = strpos($value, '-') !== false || (strpos($value, '(') !== false && strpos($value, ')') !== false);
        $number = preg_replace('/[^0-9.]/', '', $value);

        if ($number === '' || !is_numeric($number)) return null;

        $number = (float)$number;
        if ($isNegative) $number = -$number;

        return number_format($number, 2, '.', '');
    }

    // ----------------------------------------------------------- //
    private function parseStatementDate($value)
    {
        $value = trim((string)$value);

        if ($value === '') return null;

        foreach (['m/d/Y', 'Y-m-d'] as $format) {
            $date = DateTime::createFromFormat('!' . $format, $value);
            $errors = DateTime::getLastErrors();

            $hasErrors = is_array($errors) && ($errors['warning_count'] > 0 || $errors['error_count'] > 0);

            if ($date !== false && !$hasErrors) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }
}