<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class Statements extends Model
{
    protected $table = 'statements';

    protected $primaryKey = 'id';

    protected $fillable = [
        'statement_name',
        'original_file_name',
        'stored_file_name',
        'file_size',
        'total_commission',
        'uploaded_by',
        'created_at',
        'updated_at',
        'is_deleted'
    ];

    // ----------------------------------------------------------- //
    public function items()
    {
        return $this->hasMany(StatementItems::class, 'statement_id');
    }

    // ----------------------------------------------------------- //
    public static function loadStatements()
    {
        $itemsSummary = DB::table('statement_items')
            ->select('statement_id')
            ->selectRaw('COUNT(*) AS itemsCount')
            ->selectRaw('SUM(CASE WHEN reservation_id IS NOT NULL THEN 1 ELSE 0 END) AS linkedCount')
            ->where('is_deleted', 0)
            ->groupBy('statement_id');

        return DB::table('statements AS S')
            ->leftJoin('users AS U', 'U.id', '=', 'S.uploaded_by')
            ->leftJoinSub($itemsSummary, 'SI', function ($join) {
                $join->on('SI.statement_id', '=', 'S.id');
            })
            ->select('S.id', 'S.statement_name', 'S.original_file_name', 'S.total_commission', 'S.created_at')
            ->selectRaw("CONCAT(IFNULL(U.fname, ''), ' ', IFNULL(U.lname, '')) AS uploadedByName")
            ->selectRaw('IFNULL(SI.itemsCount, 0) AS itemsCount')
            ->selectRaw('IFNULL(SI.linkedCount, 0) AS linkedCount')
            ->where('S.is_deleted', 0)
            ->whereRaw('IFNULL(SI.itemsCount, 0) > IFNULL(SI.linkedCount, 0)')
            ->orderByDesc('S.created_at')
            ->orderByDesc('S.id')
            ->get();
    }

    // ----------------------------------------------------------- //
    public static function getStatementById($statementId)
    {
        return DB::table('statements AS S')
            ->leftJoin('users AS U', 'U.id', '=', 'S.uploaded_by')
            ->select('S.*')
            ->selectRaw("CONCAT(IFNULL(U.fname, ''), ' ', IFNULL(U.lname, '')) AS uploadedByName")
            ->where('S.id', (int)$statementId)
            ->where('S.is_deleted', 0)
            ->first();
    }

    // ----------------------------------------------------------- //
    public static function insertStatement(array $statementData, array $items)
    {
        try {
            return DB::transaction(function () use ($statementData, $items) {
                $statement = static::create($statementData);
                $statementId = (int)$statement->id;

                if ($statementId > 0 && count($items) > 0) {
                    foreach ($items as $index => $item) {
                        $items[$index]['statement_id'] = $statementId;
                    }

                    StatementItems::insert($items);
                }

                return $statementId > 0 ? $statementId : -1;
            });
        } catch (Throwable $e) {
            report($e);
            return -1;
        }
    }

    // ----------------------------------------------------------- //
    public static function deleteStatement($statementId)
    {
        $statementId = (int)$statementId;

        try {
            DB::transaction(function () use ($statementId) {
                $now = now();

                DB::table('statements')
                    ->where('id', $statementId)
                    ->update(['is_deleted' => 1, 'updated_at' => $now]);

                DB::table('statement_items')
                    ->where('statement_id', $statementId)
                    ->update(['is_deleted' => 1, 'updated_at' => $now]);
            });

            return 1;
        } catch (Throwable $e) {
            report($e);
            return -1;
        }
    }
}