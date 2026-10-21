<?php

namespace Tests\Unit;

use App\Http\Controllers\AgentExpensesReportController;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class ExpenseReportActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->default('Agent');
            $table->string('lname')->default('');
            $table->integer('isDeleted')->default(0);
            $table->integer('role')->default(2);
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('is_read')->default(0);
        });
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id');
            $table->integer('customer_id')->default(1);
            $table->string('status')->default('Active');
            $table->string('reservation_number')->default('RES-1');
            $table->integer('is_deleted')->default(0);
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
        });
        Schema::create('reservation_gifts', function (Blueprint $table) {
            $table->id();
            $table->integer('reservation_id')->default(-1);
            $table->integer('created_by');
            $table->string('customer_name')->default('');
            $table->date('gift_date')->default('2026-01-15');
            $table->string('gift_type')->default('Client Gifts');
            $table->decimal('amount')->default(10);
            $table->string('notes')->default('');
            $table->string('paid_by')->default('Visa');
            $table->string('last4_of_card')->default('');
            $table->dateTime('created_on');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->integer('last_modified_by')->nullable();
            $table->dateTime('last_modified_on')->nullable();
            $table->integer('is_deleted')->default(0);
        });
        DB::table('users')->insert([['id' => 1], ['id' => 2], ['id' => 9]]);
        DB::table('customers')->insert(['id' => 1, 'fname' => 'Ada', 'lname' => 'Guest']);
        DB::table('reservations')->insert([
            ['id' => 11, 'agent_id' => 1, 'is_deleted' => 0],
            ['id' => 22, 'agent_id' => 2, 'is_deleted' => 0],
            ['id' => 33, 'agent_id' => 1, 'is_deleted' => 1],
        ]);
    }

    private function viewer(int $id = 1, bool $admin = false): User
    {
        return (new User)->forceFill(['id' => $id, 'role' => $admin ? 1 : 2]);
    }

    private function expenseData(array $values = []): array
    {
        return [
            'reservation_id' => -1,
            'customer_name' => 'Guest',
            'amount' => '35.25',
            'paid_by' => 'Visa',
            'last4_of_card' => '0123',
            'gift_type' => 'Client Gifts',
            'gift_date' => '2026-01-15',
            'notes' => 'Thank-you gift',
            'beginDate' => '2026-01-01',
            'endDate' => '2026-01-31',
            ...$values,
        ];
    }

    private function existingExpense(): void
    {
        DB::table('reservation_gifts')->insert([
            'id' => 5, 'created_by' => 2, 'created_on' => '2026-01-01 00:00:00',
            'created_at' => '2026-01-01 00:00:00', 'updated_at' => '2026-01-01 00:00:00',
        ]);
    }

    public function test_agents_can_create_standalone_expenses_without_spoofing_creator(): void
    {
        $response = $this->actingAs($this->viewer())->post(route('reports.agentExpensesReport.store'), $this->expenseData([
            'created_by' => 2, 'is_deleted' => 1,
        ]));

        $response->assertRedirect(route('reports.agentExpensesReport', ['beginDate' => '2026-01-01', 'endDate' => '2026-01-31', 'expenseId' => 1]));
        $this->assertDatabaseHas('reservation_gifts', [
            'reservation_id' => -1, 'created_by' => 1, 'amount' => 35.25,
            'last4_of_card' => '0123', 'is_deleted' => 0,
        ]);
    }

    public function test_reservations_must_be_available_to_the_agent_and_not_deleted(): void
    {
        $this->actingAs($this->viewer());
        DB::table('reservations')->insert(['id' => 44, 'agent_id' => 1, 'status' => 'Canceled']);
        foreach ([22, 33, 44, 999, 0, -2] as $reservationId) {
            $this->post(route('reports.agentExpensesReport.store'), $this->expenseData(['reservation_id' => $reservationId]))
                ->assertSessionHasErrors('reservation_id');
        }
        $this->assertDatabaseCount('reservation_gifts', 0);
        $this->post(route('reports.agentExpensesReport.store'), $this->expenseData(['reservation_id' => 11]))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reservation_gifts', ['reservation_id' => 11, 'created_by' => 1]);
    }

    public function test_other_agents_cannot_read_modify_or_delete_an_expense(): void
    {
        $this->existingExpense();
        $this->actingAs($this->viewer());
        $this->patch(route('reports.agentExpensesReport.update', 5), $this->expenseData())->assertForbidden();
        $this->delete(route('reports.agentExpensesReport.destroy', 5))->assertForbidden();
        $this->get(route('reports.agentExpensesReport', ['expenseId' => 5]))->assertForbidden();
        $this->assertDatabaseHas('reservation_gifts', ['id' => 5, 'created_by' => 2, 'amount' => 10, 'is_deleted' => 0]);
    }

    public function test_admin_update_keeps_original_creator_and_soft_delete_keeps_the_record(): void
    {
        $this->existingExpense();
        $this->actingAs($this->viewer(9, true));
        $this->patch(route('reports.agentExpensesReport.update', 5), $this->expenseData(['reservation_id' => 22]))
            ->assertSessionHasNoErrors()->assertRedirect(route('reports.agentExpensesReport', [
                'beginDate' => '2026-01-01', 'endDate' => '2026-01-31', 'expenseId' => 5,
            ]));
        $this->assertDatabaseHas('reservation_gifts', [
            'id' => 5, 'created_by' => 2, 'last_modified_by' => 9, 'reservation_id' => 22, 'amount' => 35.25,
        ]);
        $this->delete(route('reports.agentExpensesReport.destroy', 5))->assertRedirect();
        $this->assertDatabaseHas('reservation_gifts', ['id' => 5, 'created_by' => 2, 'is_deleted' => 1, 'last_modified_by' => 9]);
        $this->patch(route('reports.agentExpensesReport.update', 5), $this->expenseData())->assertNotFound();
    }

    public function test_invalid_fields_do_not_write_expenses(): void
    {
        $this->actingAs($this->viewer())
            ->post(route('reports.agentExpensesReport.store'), $this->expenseData([
                'amount' => '10.555', 'last4_of_card' => '12345', 'gift_date' => 'invalid', 'paid_by' => '',
            ]))
            ->assertSessionHasErrors(['amount', 'last4_of_card', 'gift_date', 'paid_by']);
        $this->assertDatabaseCount('reservation_gifts', 0);
    }

    public function test_expense_form_loads_even_without_report_rows_and_limits_reservation_options(): void
    {
        $user = $this->viewer();
        $this->actingAs($user);
        $request = Request::create(route('reports.agentExpensesReport'), 'GET', [
            'beginDate' => '2026-01-01', 'endDate' => '2026-01-31',
        ]);
        $request->setUserResolver(fn () => $user);
        $view = (new AgentExpensesReportController)->index($request);
        $data = $view->getData();
        $this->assertSame([], $data['sections']);
        $this->assertTrue($data['expenseFormEnabled']);
        $this->assertSame([11], $data['expenseReservations']->pluck('id')->all());

        $html = view('reports.partials.financialData', [...$data, 'errors' => new ViewErrorBag])->render();
        $this->assertStringContainsString('expenseReportFormPanel', $html);
        $this->assertStringContainsString('data-customer-name="Ada Guest"', $html);
        $this->assertStringContainsString('data-expense-reservation', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringContainsString('name="last4_of_card"', $html);
    }

    public function test_editing_an_expense_keeps_inline_form_open_and_requires_delete_confirmation(): void
    {
        $this->existingExpense();
        $user = $this->viewer(2);
        $this->actingAs($user);
        $request = Request::create(route('reports.agentExpensesReport'), 'GET', [
            'beginDate' => '2026-01-01', 'endDate' => '2026-01-31', 'expenseId' => 5,
        ]);
        $request->setUserResolver(fn () => $user);
        $data = (new AgentExpensesReportController)->index($request)->getData();
        $html = view('reports.partials.financialData', [...$data, 'errors' => new ViewErrorBag])->render();

        $this->assertSame([22], $data['expenseReservations']->pluck('id')->all());
        $this->assertMatchesRegularExpression('/<details\b[^>]*id="expenseReportFormPanel"[^>]*\sopen\b/', $html);
        $this->assertStringContainsString('data-report-open-dialog="expenseDeleteDialog"', $html);
        $this->assertStringContainsString('Are you sure you want to delete this record?', $html);
        $this->assertStringContainsString('name="_method" value="DELETE"', $html);
    }
}
