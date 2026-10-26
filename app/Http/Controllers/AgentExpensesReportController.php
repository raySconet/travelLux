<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AgentExpensesReportController extends DataReportController
{
    protected string $report = 'agentExpensesReport';

    public function index(Request $request)
    {
        $response = parent::index($request);
        if (! $response instanceof View) {
            return $response;
        }

        $input = $request->validate(['expenseId' => ['nullable', 'integer', 'min:1']]);
        $expense = isset($input['expenseId']) ? $this->authorizedExpense($request, $input['expenseId']) : null;
        $reservations = DB::table('reservations as r')
            ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
            ->where('r.is_deleted', 0)
            ->whereIn('r.status', ['Active', 'Paid in Full', 'Canceled - Commission Protected'])
            ->where('r.agent_id', $expense->created_by ?? $request->user()->id)
            ->orderBy('r.reservation_number')
            ->get(['r.id', 'r.reservation_number', 'c.fname as customer_fname', 'c.lname as customer_lname']);

        return $response->with([
            'expense' => $expense,
            'expenseReservations' => $reservations,
            'expenseFormEnabled' => true,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedExpense($request);
        $expenseId = DB::table('reservation_gifts')->insertGetId([
            ...$data,
            'created_by' => $request->user()->id,
            'created_on' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'is_deleted' => 0,
        ]);

        return $this->reportRedirect($request, true, $expenseId)->with('expense_status', 'Expense saved.');
    }

    public function update(Request $request, int $expense)
    {
        $record = $this->authorizedExpense($request, $expense);
        $data = $this->validatedExpense($request);
        DB::table('reservation_gifts')->where('id', $record->id)->update([
            ...$data,
            'last_modified_by' => $request->user()->id,
            'last_modified_on' => now(),
            'updated_at' => now(),
        ]);

        return $this->reportRedirect($request, false, $record->id)->with('expense_status', 'Expense updated.');
    }

    public function destroy(Request $request, int $expense)
    {
        $record = $this->authorizedExpense($request, $expense);
        $this->validateReportFilters($request);
        DB::table('reservation_gifts')->where('id', $record->id)->update([
            'is_deleted' => 1,
            'last_modified_by' => $request->user()->id,
            'last_modified_on' => now(),
            'updated_at' => now(),
        ]);

        return $this->reportRedirect($request)->with('expense_status', 'Expense deleted.');
    }

    private function authorizedExpense(Request $request, int $id): object
    {
        $expense = DB::table('reservation_gifts')->where('id', $id)->where('is_deleted', 0)->first();
        abort_unless($expense, 404);
        abort_unless($request->user()->isAdmin() || (int) $expense->created_by === (int) $request->user()->id, 403);

        return $expense;
    }

    private function validatedExpense(Request $request): array
    {
        $this->validateReportFilters($request);
        $data = $request->validate([
            'reservation_id' => [
                'required', 'integer', 'min:-1', 'not_in:0',
                Rule::when((int) $request->input('reservation_id') !== -1, [
                    Rule::exists('reservations', 'id')->where(function (Builder $query) use ($request) {
                        $query->where('is_deleted', 0);
                        $query->whereIn('status', ['Active', 'Paid in Full', 'Canceled - Commission Protected']);
                        if (! $request->user()->isAdmin()) {
                            $query->where('agent_id', $request->user()->id);
                        }
                    }),
                ]),
            ],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99', 'decimal:0,2'],
            'paid_by' => ['required', 'string', 'max:255'],
            'last4_of_card' => ['nullable', 'string', 'max:4'],
            'gift_type' => ['required', 'string', 'max:255'],
            'gift_date' => ['required', 'date_format:Y-m-d'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ]);

        foreach (['customer_name', 'last4_of_card', 'notes'] as $field) {
            $data[$field] = $data[$field] ?? '';
        }

        return $data;
    }

    private function validateReportFilters(Request $request): void
    {
        $request->validate([
            'beginDate' => ['nullable', 'date_format:Y-m-d'],
            'endDate' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:beginDate'],
            'agentId' => ['nullable', 'integer', 'min:1'],
            'search' => ['nullable', 'string', 'max:200'],
        ]);
    }

    private function reportRedirect(Request $request, bool $created = false, ?int $expenseId = null)
    {
        $filters = array_filter($request->only(['beginDate', 'endDate', 'agentId', 'search']), fn ($value) => $value !== null && $value !== '');
        if ($created) {
            unset($filters['agentId'], $filters['search']);
        }
        if ($expenseId !== null) {
            $filters['expenseId'] = $expenseId;
        }

        return redirect()->route('reports.agentExpensesReport', $filters);
    }
}
