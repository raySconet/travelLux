<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Reports\FinancialReports;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FinancialReportsTest extends TestCase
{
    private array $filters = ['beginDate' => '2026-01-01', 'endDate' => '2026-01-31'];

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname')->default('');
            $table->integer('isDeleted')->default(0);
            $table->integer('is_disabled')->default(0);
            $table->string('mentor_ids')->default('');
            $table->decimal('commission')->default(60);
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname')->default('');
        });
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id');
            $table->integer('customer_id')->default(1);
            $table->string('reservation_number');
            $table->string('status')->default('Paid in Full');
            $table->date('checkin_date')->default('2026-01-10');
            $table->date('checkout_date')->default('2026-01-20');
            $table->decimal('agency_commission')->default(1000);
            $table->decimal('agent_commission')->default(600);
            $table->decimal('agent_commission_percentage')->default(60);
            $table->integer('commission_received')->default(1);
            $table->integer('agent_commission_received')->default(0);
            $table->integer('mentor_commission_received')->default(0);
            $table->integer('non_commissionable')->default(0);
            $table->integer('is_deleted')->default(0);
            $table->integer('agent_personal_travel')->default(0);
            $table->integer('look_up')->default(0);
            $table->integer('document_fee')->default(0);
        });
        Schema::create('paid_commissions', function (Blueprint $table) {
            $table->id();
            $table->integer('reservation_id');
            $table->integer('agent_id');
            $table->integer('is_agent')->default(1);
            $table->date('check_date')->default('2026-01-15');
            $table->decimal('amount')->default(600);
        });
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('mentor_id');
            $table->decimal('mentor_commission')->default(10);
            $table->date('mentor_begin_date')->default('2026-01-10');
            $table->date('mentor_end_date')->default('2026-01-20');
            $table->integer('mentor_pays')->default(0);
            $table->integer('is_deleted')->default(0);
        });
        Schema::create('reservation_gifts', function (Blueprint $table) {
            $table->id();
            $table->integer('reservation_id')->nullable();
            $table->integer('created_by');
            $table->string('customer_name')->default('Guest');
            $table->string('paid_by')->default('Agent');
            $table->string('last4_of_card')->default('0123');
            $table->string('gift_type')->default('Gift');
            $table->string('notes')->default('');
            $table->date('gift_date')->default('2026-01-31');
            $table->decimal('amount')->default(30);
            $table->integer('is_deleted')->default(0);
        });

        DB::table('users')->insert([
            ['id' => 1, 'fname' => 'First', 'lname' => 'Agent'],
            ['id' => 2, 'fname' => 'Second', 'lname' => 'Agent'],
            ['id' => 3, 'fname' => 'Mentor', 'lname' => 'Agent'],
        ]);
        DB::table('customers')->insert(['id' => 1, 'fname' => 'Customer']);
    }

    private function viewer(int $id = 1, bool $admin = false): User
    {
        return (new User)->forceFill(['id' => $id, 'role' => $admin ? 1 : 2]);
    }

    private function reservation(int $id, array $values = []): void
    {
        DB::table('reservations')->insert([
            'id' => $id, 'agent_id' => 1, 'reservation_number' => 'R-'.$id, ...$values,
        ]);
    }

    public function test_unpaid_report_preserves_legacy_agent_date_selection_and_secure_scope(): void
    {
        $this->reservation(1);
        $this->reservation(2, ['checkout_date' => '2026-02-01']);
        $this->reservation(3, ['agent_id' => 2]);
        $this->reservation(4, ['non_commissionable' => 1]);
        $this->reservation(5, ['commission_received' => 0]);
        $this->reservation(6, ['is_deleted' => 1]);
        $this->reservation(7, ['status' => 'Active']);

        $sections = (new FinancialReports)->run(
            'reservationsNotPaidByTraveluxReport',
            [...$this->filters, 'agentId' => 2],
            $this->viewer(),
        );

        $this->assertCount(1, $sections);
        $this->assertSame(['R-1', 'R-2'], $sections[0]['rows']->pluck('reservation_number')->all());
        $this->assertSame(1200.0, $sections[0]['totals']['unpaid']);
        $this->assertSame(1200.0, $sections[0]['totals']['remitted']);
    }

    public function test_paid_report_preserves_one_row_per_matching_check_including_mentor_checks(): void
    {
        $this->reservation(1, ['agent_commission_received' => 1, 'checkout_date' => '2025-12-31']);
        $this->reservation(2, ['agent_commission_received' => 1]);
        $this->reservation(3, ['agent_commission_received' => 1]);
        DB::table('paid_commissions')->insert([
            ['reservation_id' => 1, 'agent_id' => 1, 'is_agent' => 1, 'check_date' => '2026-01-01', 'amount' => 300],
            ['reservation_id' => 1, 'agent_id' => 1, 'is_agent' => 1, 'check_date' => '2026-01-31', 'amount' => 300],
            ['reservation_id' => 1, 'agent_id' => 3, 'is_agent' => 0, 'check_date' => '2026-01-31', 'amount' => 100],
            ['reservation_id' => 2, 'agent_id' => 3, 'is_agent' => 0, 'check_date' => '2026-01-15', 'amount' => 100],
            ['reservation_id' => 3, 'agent_id' => 1, 'is_agent' => 1, 'check_date' => '2026-02-01', 'amount' => 600],
        ]);

        $sections = (new FinancialReports)->run('reservationsPaidByTraveluxReport', $this->filters, $this->viewer());

        $this->assertCount(1, $sections);
        $this->assertSame(['R-1', 'R-1', 'R-1', 'R-2'], $sections[0]['rows']->pluck('reservation_number')->all());
        $this->assertSame(2400.0, $sections[0]['totals']['agent_commission']);
        $this->assertSame(0.0, $sections[0]['totals']['unpaid']);
    }

    public function test_final_agency_report_preserves_each_agent_payment_and_repeated_commission(): void
    {
        $this->reservation(1, ['agent_commission_received' => 1]);
        DB::table('paid_commissions')->insert([
            ['reservation_id' => 1, 'agent_id' => 1, 'is_agent' => 1, 'check_date' => '2026-01-05', 'amount' => 200],
            ['reservation_id' => 1, 'agent_id' => 1, 'is_agent' => 1, 'check_date' => '2026-01-31', 'amount' => 350],
            ['reservation_id' => 1, 'agent_id' => 3, 'is_agent' => 0, 'check_date' => '2026-01-31', 'amount' => 100],
            ['reservation_id' => 1, 'agent_id' => 1, 'is_agent' => 1, 'check_date' => '2026-02-01', 'amount' => 50],
        ]);

        $sections = (new FinancialReports)->run('finalAgencyCommissionReport', $this->filters, $this->viewer(1, true));

        $this->assertCount(2, $sections[0]['rows']);
        $this->assertSame(2000.0, $sections[0]['totals']['total_commission']);
        $this->assertSame(800.0, $sections[0]['totals']['agency_commission']);
        $this->assertSame(550.0, $sections[0]['totals']['paid_amount']);
        $this->assertSame(['2026-01-05', '2026-01-31'], $sections[0]['rows']->pluck('check_date')->sort()->values()->all());
    }

    public function test_expenses_include_standalone_records_and_preserve_creator_scope(): void
    {
        $this->reservation(1, ['is_deleted' => 1]);
        DB::table('reservation_gifts')->insert([
            ['created_by' => 1, 'reservation_id' => null, 'gift_date' => '2026-01-01', 'is_deleted' => 0],
            ['created_by' => 1, 'reservation_id' => 1, 'gift_date' => '2026-01-31', 'is_deleted' => 0],
            ['created_by' => 2, 'reservation_id' => null, 'gift_date' => '2026-01-31', 'is_deleted' => 0],
            ['created_by' => 1, 'reservation_id' => null, 'gift_date' => '2026-01-31', 'is_deleted' => 1],
            ['created_by' => 1, 'reservation_id' => null, 'gift_date' => '2026-02-01', 'is_deleted' => 0],
        ]);

        $sections = (new FinancialReports)->run('agentExpensesReport', [...$this->filters, 'agentId' => 2], $this->viewer());

        $this->assertCount(1, $sections);
        $this->assertCount(2, $sections[0]['rows']);
        $this->assertSame(60.0, $sections[0]['totals']['amount']);
        $this->assertSame('0123', $sections[0]['rows']->first()['last4_of_card']);
        $this->assertNull($sections[0]['rows']->last()['reservation_number']);

        $admin = (new FinancialReports)->run('agentExpensesReport', [...$this->filters, 'agentId' => 2], $this->viewer(1, true));
        $this->assertSame(60.0, $admin[0]['totals']['amount']);
        $this->assertCount(2, $admin[0]['rows']);
    }

    public function test_total_commission_preserves_mentor_dates_fees_and_unknown_scope(): void
    {
        $this->reservation(1, ['agent_commission_received' => 1, 'look_up' => 1, 'document_fee' => 1]);
        $this->reservation(2, ['agent_commission_received' => 1, 'agency_commission' => 0, 'agent_commission' => 0]);
        $this->reservation(3, ['agent_id' => 0, 'agent_commission_received' => 1, 'agency_commission' => 200]);
        DB::table('paid_commissions')->insert([
            ['reservation_id' => 1, 'agent_id' => 1],
            ['reservation_id' => 1, 'agent_id' => 1],
            ['reservation_id' => 2, 'agent_id' => 1],
        ]);
        DB::table('mentors')->insert([
            ['user_id' => 1, 'mentor_id' => 3, 'mentor_commission' => 50, 'mentor_begin_date' => '2026-01-01', 'mentor_end_date' => '2026-12-31'],
            ['user_id' => 1, 'mentor_id' => 3, 'mentor_commission' => 10, 'mentor_begin_date' => '2026-01-10', 'mentor_end_date' => '2026-01-20'],
        ]);

        $report = new FinancialReports;
        $sections = $report->run('totalCommissionReport', $this->filters, $this->viewer(1, true));

        $this->assertCount(2, $sections);
        $this->assertCount(3, $sections[0]['rows']);
        $this->assertSame(200.0, $sections[0]['totals']['mentor_commission']);
        $this->assertSame(600.0, $sections[0]['totals']['agency_commission']);
        $this->assertSame(50.0, $sections[0]['totals']['fees']);
        $this->assertSame(0, $sections[0]['rows']->last()['agent_percentage']);
        $this->assertSame(200.0, $sections[1]['rows']->firstWhere('agent', 'Unknown')['total']);
        $this->assertSame(1250.0, $sections[1]['rows']->firstWhere('agent', 'Total')['total']);

        $own = $report->run('totalCommissionReport', $this->filters, $this->viewer());
        $this->assertNull($own[1]['rows']->firstWhere('agent', 'Unknown'));
    }

    public function test_total_commission_preserves_legacy_owner_summary_adjustment(): void
    {
        DB::table('users')->insert([
            ['id' => 8, 'fname' => 'DeeAna', 'lname' => 'Owner'],
            ['id' => 82, 'fname' => 'Special', 'lname' => 'Agent'],
        ]);
        $this->reservation(1, ['agent_commission_received' => 1, 'look_up' => 1, 'document_fee' => 1]);
        $this->reservation(2, ['agent_id' => 8, 'agent_commission_received' => 1]);
        $this->reservation(3, ['agent_id' => 82, 'agent_commission_received' => 1]);
        DB::table('paid_commissions')->insert([
            ['reservation_id' => 1, 'agent_id' => 1],
            ['reservation_id' => 2, 'agent_id' => 8],
            ['reservation_id' => 3, 'agent_id' => 82],
        ]);

        $sections = (new FinancialReports)->run('totalCommissionReport', $this->filters, $this->viewer(1, true));
        $summary = collect($sections)->firstWhere('kind', 'commission_summary');

        $this->assertSame('DeeAna', $summary['title']);
        $this->assertSame(1200.0, $summary['summary'][0]['value']);
        $this->assertSame(600.0, $summary['summary'][1]['value']);
        $this->assertSame(1200.0, $summary['summary'][2]['value']);
        $this->assertSame(1200.0, $summary['summary'][3]['value']);
        $this->assertSame(1225.0, $summary['rows']->firstWhere('agent', 'Total')['total']);
        $this->assertNull($summary['rows']->firstWhere('agent', 'DeeAna Owner'));
    }

    public function test_total_commission_preserves_undated_unpaid_mentor_branch(): void
    {
        DB::table('users')->where('id', 2)->update(['mentor_ids' => '1']);
        $this->reservation(1, ['agent_commission_received' => 1]);
        $this->reservation(2, ['agent_id' => 2, 'checkin_date' => '2025-10-01', 'checkout_date' => '2025-10-05']);
        DB::table('paid_commissions')->insert(['reservation_id' => 1, 'agent_id' => 1]);

        $sections = (new FinancialReports)->run('totalCommissionReport', $this->filters, $this->viewer(1, true));

        $this->assertSame(['R-1', 'R-2'], $sections[0]['rows']->pluck('reservation_number')->all());
        $this->assertSame(1200.0, $sections[0]['totals']['agent_commission']);

        $own = (new FinancialReports)->run('totalCommissionReport', $this->filters, $this->viewer());
        $this->assertSame(['R-1'], $own[0]['rows']->pluck('reservation_number')->all());
    }

    public function test_financial_layouts_render_their_sections_and_escape_customer_text(): void
    {
        DB::table('customers')->where('id', 1)->update(['fname' => '<script>Example</script>']);
        $this->reservation(1);
        $this->reservation(2, ['agent_commission_received' => 1]);
        DB::table('paid_commissions')->insert(['reservation_id' => 2, 'agent_id' => 1]);
        DB::table('reservation_gifts')->insert(['created_by' => 1]);
        $service = new FinancialReports;

        foreach ([
            'reservationsNotPaidByTraveluxReport',
            'reservationsPaidByTraveluxReport',
            'agentExpensesReport',
            'finalAgencyCommissionReport',
            'totalCommissionReport',
        ] as $reportKey) {
            $html = view('reports.partials.financialData', [
                'reportKey' => $reportKey,
                'sections' => $service->run($reportKey, $this->filters, $this->viewer(1, true)),
                'filters' => $this->filters,
            ])->render();

            $this->assertStringContainsString('<table', $html, $reportKey);
            $this->assertStringNotContainsString('<script>Example</script>', $html, $reportKey);
            if (in_array($reportKey, ['reservationsNotPaidByTraveluxReport', 'reservationsPaidByTraveluxReport', 'totalCommissionReport'])) {
                $this->assertStringContainsString('01-20-2026', $html, $reportKey);
            }
            if ($reportKey === 'finalAgencyCommissionReport') {
                $this->assertStringContainsString('Commission breakdown', $html);
                $this->assertStringContainsString('Agent - 60%', $html);
            }
            if ($reportKey === 'totalCommissionReport') {
                $this->assertStringContainsString(route('reservations.reservationDetails', 2), $html);
            }
        }
    }

    public function test_final_agency_preserves_empty_groups_for_paid_check_recipients(): void
    {
        $this->reservation(1, ['agent_commission_received' => 0]);
        DB::table('paid_commissions')->insert(['reservation_id' => 1, 'agent_id' => 1]);

        $sections = (new FinancialReports)->run('finalAgencyCommissionReport', $this->filters, $this->viewer(1, true));

        $this->assertCount(1, $sections);
        $this->assertSame('First Agent', $sections[0]['title']);
        $this->assertCount(0, $sections[0]['rows']);
        $this->assertSame(0.0, $sections[0]['totals']['paid_amount']);
        $html = view('reports.partials.financialData', [
            'sections' => $sections, 'reportKey' => 'finalAgencyCommissionReport', 'filters' => $this->filters,
        ])->render();
        $this->assertStringContainsString('First Agent', $html);
        $this->assertStringContainsString('Total Paid', $html);
    }

    public function test_total_mentor_branch_keeps_legacy_excluded_account_and_loose_id_matching(): void
    {
        DB::table('users')->insert(['id' => 6680, 'fname' => 'Legacy', 'mentor_ids' => ' 01']);
        $this->reservation(1, ['agent_commission_received' => 1]);
        $this->reservation(2, ['agent_id' => 6680, 'checkin_date' => '2025-10-01', 'checkout_date' => '2025-10-05']);
        DB::table('paid_commissions')->insert(['reservation_id' => 1, 'agent_id' => 1]);

        $sections = (new FinancialReports)->run('totalCommissionReport', $this->filters, $this->viewer(1, true));

        $this->assertSame(['R-1', 'R-2'], $sections[0]['rows']->pluck('reservation_number')->all());
        $this->assertSame(1200.0, $sections[0]['totals']['agent_commission']);
    }
}
