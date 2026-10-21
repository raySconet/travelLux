<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UnknownReservationsReportController extends DataReportController
{
    protected string $report = 'unknownReservationsReport';

    public function updateChecked(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);
        $input = $request->validate([
            'reservation_id' => ['required', 'integer', 'min:1'],
            'checked' => ['required', 'boolean'],
            'search' => ['nullable', 'string', 'max:200'],
        ]);

        $reservation = DB::table('reservations')
            ->where('id', $input['reservation_id'])
            ->where('agent_id', 0)
            ->where('is_deleted', 0);
        abort_unless((clone $reservation)->exists(), 404);

        // Match the legacy checked-flag update and its editor/timestamp fields.
        $reservation->update([
            'unknown_reservation_checked_flag' => (int) $request->boolean('checked'),
            'last_modified_by' => $request->user()->id,
            'last_modified_on' => now(),
        ]);

        if ($request->expectsJson()) {
            $unchecked = DB::table('reservations')->where('agent_id', 0)->where('is_deleted', 0)
                ->where('unknown_reservation_checked_flag', 0);

            return response()->json([
                'checked' => $request->boolean('checked'),
                'unchecked_total' => (clone $unchecked)->where('commission_claimed', 0)->sum('agency_commission'),
                'claimed_total' => (clone $unchecked)->where('commission_claimed', 1)->sum('agency_commission'),
            ]);
        }

        return to_route('reports.unknownReservationsReport', array_filter([
            'search' => $input['search'] ?? null,
        ]));
    }

    public function edit(Request $request, int $reservation)
    {
        abort_unless($request->user()?->isAdmin(), 403);
        $record = $this->unknownReservation($reservation)->first([
            'id', 'customer_id', 'product_id', 'reservation_number', 'agency_commission',
            'unknown_reservation_date', 'reservation_notes',
        ]);
        abort_unless($record, 404);
        $record->month = substr((string) $record->unknown_reservation_date, 0, 7);
        $record->agency_commission = $record->agency_commission === null
            ? null : number_format((float) $record->agency_commission, 2, '.', '');

        // Same dropdown eligibility/order as loadCustomersListForDropdown(-1)
        // and loadProductsList in the original unknown-reservation modal.
        $customers = DB::table('customers')->where('is_deleted', 0)->where('agent_id', '<>', 0)
            ->select('id', 'fname', 'mname', 'lname')->get()->map(fn ($customer) => [
                'id' => $customer->id,
                'name' => trim(($customer->lname ?? '').', '.($customer->fname ?? '').' '.($customer->mname ?? '')),
            ])->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values();
        $products = DB::table('products')->where('is_deleted', 0)
            ->orderByRaw('id = 1 DESC')->orderByRaw('id = 3 DESC')->orderBy('product_name')
            ->get(['id', 'product_name as name']);

        return response()->json([
            'reservation' => $record,
            'customers' => $customers,
            'products' => $products,
            'update_url' => route('reports.unknownReservationsReport.update', $reservation),
        ]);
    }

    public function update(Request $request, int $reservation)
    {
        abort_unless($request->user()?->isAdmin(), 403);
        $query = $this->unknownReservation($reservation);
        abort_unless((clone $query)->exists(), 404);
        $input = $request->validate([
            'customer_id' => ['required', 'integer', 'min:1',
                Rule::exists('customers', 'id')->where(fn (Builder $query) => $query->where('is_deleted', 0)->where('agent_id', '<>', 0))],
            'product_id' => ['nullable', 'integer', 'min:-1',
                Rule::when((int) $request->input('product_id') > 0, Rule::exists('products', 'id')->where('is_deleted', 0))],
            'reservation_number' => ['required', 'string', 'max:255'],
            'agency_commission' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'month' => ['required', 'date_format:Y-m'],
            'reservation_notes' => ['nullable', 'string', 'max:65535'],
            'search' => ['nullable', 'string', 'max:200'],
        ]);

        // Commissions::unknownReservationFormSubmit updates only these fields;
        // empty/zero commission is stored as NULL and month is its first day.
        $query->update([
            'customer_id' => $input['customer_id'],
            'product_id' => ($input['product_id'] ?? -1) ?: -1,
            'reservation_number' => $input['reservation_number'],
            'agency_commission' => empty($input['agency_commission']) ? null : $input['agency_commission'],
            'unknown_reservation_date' => CarbonImmutable::createFromFormat('!Y-m', $input['month'])->toDateString(),
            'reservation_notes' => $input['reservation_notes'] ?? null,
            'last_modified_by' => $request->user()->id,
            'last_modified_on' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['saved' => true]);
        }

        return to_route('reports.unknownReservationsReport', array_filter(['search' => $input['search'] ?? null]));
    }

    private function unknownReservation(int $id): Builder
    {
        return DB::table('reservations')->where('id', $id)->where('agent_id', 0)->where('is_deleted', 0);
    }
}
