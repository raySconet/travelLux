<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Reports\ReportCatalog;
use App\Services\Reports\ReportValue;
use App\Services\Reports\SalesReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DataReportController extends Controller
{
    protected string $report;

    public function index(Request $request)
    {
        $definition = ReportCatalog::get($this->report);
        $user = $request->user();
        abort_unless($user && (! $definition['admin'] || $user->isAdmin()), 403);

        $yearRange = in_array($this->report, ['agentExpensesReport', 'bookedTripsByStateReport']);
        $monthToDate = in_array($this->report, ['productSalesByAgentReport', 'agentSalesByProductReport']);
        $rollingMonth = in_array($this->report, [
            'reservationsNotPaidByTraveluxReport', 'reservationsPaidByTraveluxReport',
            'aliasTotalSalesReport', 'aliasTotalGrossCommission', 'totalSalesReport',
            'allReservationsByDateReport', 'allTripsByTravelDateReport', 'top15ExpensiveTripsReport',
            'finalAgencyCommissionReport',
            'productSalesWithDepositByAgentReport',
        ]);
        $input = array_replace([
            'beginDate' => ($yearRange ? now()->startOfYear() : ($rollingMonth ? now()->subMonth() : now()->startOfMonth()))->toDateString(),
            'endDate' => ($yearRange ? now()->endOfYear() : ($rollingMonth || $monthToDate ? now() : now()->endOfMonth()))->toDateString(),
            'dateBasis' => 'checkin',
            'year' => now()->year,
            'beginYear' => now()->year - 1,
            'endYear' => now()->year,
        ], $request->only(['beginDate', 'endDate', 'dateBasis', 'year', 'beginYear', 'endYear', 'agentId', 'state', 'search', 'export']));

        $filters = validator($input, [
            'beginDate' => ['required', 'date_format:Y-m-d'],
            'endDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:beginDate'],
            'dateBasis' => ['required', Rule::in(['checkin', 'book', 'both'])],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'beginYear' => ['required', 'integer', 'between:1900,2100'],
            'endYear' => ['required', 'integer', 'between:1900,2100', 'gte:beginYear'],
            'agentId' => ['nullable', 'integer', 'min:1'],
            'state' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:200'],
            'export' => ['nullable', Rule::in(['csv'])],
        ])->validate();

        // Never allow a submitted agent filter to widen an agent's access.
        if (! $user->isAdmin()) {
            $filters['agentId'] = $user->id;
        }
        $sections = app($definition['service'])->run($this->report, $filters, $user);
        $search = trim($filters['search'] ?? '');
        foreach ($sections as &$section) {
            $section['rows'] = collect($section['rows']);
            if ($search !== '' && $definition['service'] !== SalesReports::class && mb_stripos($section['title'] ?? '', $search) === false) {
                $section['rows'] = $section['rows']->filter(function ($row) use ($section, $search) {
                    foreach ($section['columns'] as $key => $label) {
                        if (mb_stripos(ReportValue::format(data_get($row, $key), $section['formats'][$key] ?? null), $search) !== false) {
                            return true;
                        }
                    }

                    return false;
                })->values();
            }
        }
        unset($section);

        if (($filters['export'] ?? null) === 'csv') {
            $range = $definition['year'] ? (string) $filters['year']
                : ($definition['years'] ? $filters['beginYear'].'-'.$filters['endYear']
                    : ($definition['dates'] ? $filters['beginDate'].'-'.$filters['endDate'] : 'all-dates'));

            return response()->streamDownload(function () use ($sections, $definition) {
                $stream = fopen('php://output', 'w');
                fwrite($stream, "\xEF\xBB\xBF");
                fputcsv($stream, [$definition['title']], ',', '"', '');
                foreach ($sections as $section) {
                    fputcsv($stream, [$section['title'] ?? ''], ',', '"', '');
                    fputcsv($stream, array_values($section['columns']), ',', '"', '');
                    foreach ($section['rows'] as $row) {
                        $values = [];
                        foreach ($section['columns'] as $key => $label) {
                            $values[] = ReportValue::csv(ReportValue::format(data_get($row, $key), $section['formats'][$key] ?? null));
                        }
                        fputcsv($stream, $values, ',', '"', '');
                    }
                    if (! empty($section['totals'])) {
                        $values = [];
                        foreach ($section['columns'] as $key => $label) {
                            $values[] = ReportValue::csv(ReportValue::format($section['totals'][$key] ?? (count($values) === 0 ? 'Total' : ''), $section['formats'][$key] ?? null));
                        }
                        fputcsv($stream, $values, ',', '"', '');
                    }
                    fputcsv($stream, [], ',', '"', '');
                }
                fclose($stream);
            }, $this->report.'-'.$range.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        $agents = $user->isAdmin() && $definition['agents']
            ? User::where('isDeleted', 0)->where('is_disabled', '!=', 1)->where('id', '!=', 6680)
                ->orderBy('fname')->orderBy('lname')->get(['id', 'fname', 'lname'])
            : collect();
        $reportKey = $this->report;
        $states = $definition['state'] ? DB::table('states')->orderBy('name')->pluck('name') : collect();

        return view('reports.dataReport', compact('definition', 'filters', 'sections', 'agents', 'states', 'reportKey'));
    }
}
