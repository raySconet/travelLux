<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class StatementItems extends Model
{
    protected $table = 'statement_items';

    protected $primaryKey = 'id';

    protected $fillable = [
        'statement_id',
        'reservation_number',
        'client_name',
        'group_number',
        'agency_commission',
        'checkout_date',
        'hotel_name',
        'reservation_id',
        'sort_order',
        'created_at',
        'updated_at',
        'is_deleted'
    ];

    // ----------------------------------------------------------- //
    public function statement()
    {
        return $this->belongsTo(Statements::class, 'statement_id');
    }

    // ----------------------------------------------------------- //
    public static function getStatementItems($statementId)
    {
        return DB::table('statement_items')
            ->select('id', 'reservation_number', 'client_name', 'group_number', 'agency_commission',
                     'checkout_date', 'hotel_name', 'reservation_id')
            ->where('statement_id', (int)$statementId)
            ->where('is_deleted', 0)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    // ----------------------------------------------------------- //
    public static function getStatementAttachmentCounts($reservationIds)
    {
        $counts = [];

        $ids = array_values(array_filter(array_map('intval', (array)$reservationIds)));

        if (count($ids) == 0) return $counts;

        $rows = DB::table('statement_items AS SI')
            ->join('statements AS S', function ($join) {
                $join->on('S.id', '=', 'SI.statement_id')->where('S.is_deleted', 0);
            })
            ->select('SI.reservation_id')
            ->selectRaw('COUNT(*) AS attachedCount')
            ->where('SI.is_deleted', 0)
            ->whereIn('SI.reservation_id', array_unique($ids))
            ->groupBy('SI.reservation_id')
            ->get();

        foreach ($rows as $row) {
            $counts[(int)$row->reservation_id] = (int)$row->attachedCount;
        }

        return $counts;
    }

    // ----------------------------------------------------------- //
    public static function getReservationForStatements($reservationId)
    {
        return DB::table('reservations AS R')
            ->leftJoin('customers AS C', 'C.id', '=', 'R.customer_id')
            ->select('R.id', 'R.reservation_number', 'R.group_number', 'C.fname', 'C.lname')
            ->where('R.id', (int)$reservationId)
            ->where('R.is_deleted', 0)
            ->first();
    }

    // ----------------------------------------------------------- //
    public static function getAttachedStatementItems($reservationId)
    {
        return DB::table('statement_items AS SI')
            ->join('statements AS S', function ($join) {
                $join->on('S.id', '=', 'SI.statement_id')->where('S.is_deleted', 0);
            })
            ->select('SI.id', 'SI.statement_id', 'SI.reservation_number', 'SI.client_name', 'SI.group_number',
                     'SI.agency_commission', 'SI.checkout_date', 'SI.hotel_name',
                     'S.statement_name', 'S.created_at AS statementCreatedAt')
            ->where('SI.reservation_id', (int)$reservationId)
            ->where('SI.is_deleted', 0)
            ->orderByDesc('S.created_at')
            ->orderBy('SI.sort_order')
            ->get();
    }

    // ----------------------------------------------------------- //
    public static function getUnlinkedStatementItems()
    {
        return DB::table('statement_items AS SI')
            ->join('statements AS S', function ($join) {
                $join->on('S.id', '=', 'SI.statement_id')->where('S.is_deleted', 0);
            })
            ->select('SI.id', 'SI.statement_id', 'SI.reservation_number', 'SI.client_name', 'SI.group_number',
                     'SI.agency_commission', 'SI.checkout_date', 'SI.hotel_name',
                     'S.statement_name', 'S.created_at AS statementCreatedAt')
            ->whereNull('SI.reservation_id')
            ->where('SI.is_deleted', 0)
            ->orderByDesc('S.created_at')
            ->orderByDesc('S.id')
            ->orderBy('SI.sort_order')
            ->get();
    }

    // ----------------------------------------------------------- //
    public static function attachStatementItem($statementItemId, $reservationId)
    {
        try {
            $affected = DB::table('statement_items AS SI')
                ->join('statements AS S', function ($join) {
                    $join->on('S.id', '=', 'SI.statement_id')->where('S.is_deleted', 0);
                })
                ->where('SI.id', (int)$statementItemId)
                ->whereNull('SI.reservation_id')
                ->where('SI.is_deleted', 0)
                ->update([
                    'SI.reservation_id' => (int)$reservationId,
                    'SI.updated_at'     => now()
                ]);

            return $affected > 0 ? 1 : 0;
        } catch (Throwable $e) {
            report($e);
            return -1;
        }
    }

    // ----------------------------------------------------------- //
    public static function detachStatementItem($statementItemId, $reservationId)
    {
        try {
            $affected = DB::table('statement_items')
                ->where('id', (int)$statementItemId)
                ->where('reservation_id', (int)$reservationId)
                ->where('is_deleted', 0)
                ->update([
                    'reservation_id' => null,
                    'updated_at'     => now()
                ]);

            return $affected > 0 ? 1 : 0;
        } catch (Throwable $e) {
            report($e);
            return -1;
        }
    }
}