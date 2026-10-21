<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Reports\SalesReports;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class SalesReportsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('fname');
            $table->string('lname')->default('');
            $table->string('alias')->default('');
            $table->integer('isDeleted')->default(0);
            $table->integer('is_disabled')->default(0);
            $table->integer('include_in_alias_reports')->default(0);
        });
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('product_name');
        });
        Schema::create('destinations', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('destination_name');
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('fname');
            $table->string('lname')->default('');
        });
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id');
            $table->integer('customer_id')->default(1);
            $table->integer('product_id')->default(1);
            $table->integer('destination_id')->default(1);
            $table->string('reservation_number')->default('TEST');
            $table->string('status')->default('Paid in Full');
            $table->integer('is_deleted')->default(0);
            $table->date('checkin_date')->default('2026-01-10');
            $table->date('checkout_date')->default('2026-01-15');
            $table->dateTime('created_on')->default('2026-01-01 12:00:00');
            $table->decimal('reservation_cost', 12, 2)->default(100);
            $table->decimal('agency_commission', 12, 2)->default(20);
            $table->decimal('agent_commission', 12, 2)->default(12);
        });
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id');
            $table->string('payment_type')->default('Deposit');
            $table->integer('is_deleted')->default(0);
            $table->dateTime('payment_date');
        });

        DB::table('users')->insert([
            ['id' => 10, 'fname' => 'Amy', 'alias' => 'Agent A'],
            ['id' => 20, 'fname' => 'Ben', 'alias' => 'Agent B'],
            ['id' => 6680, 'fname' => 'Excluded', 'alias' => 'Excluded'],
        ]);
        DB::table('products')->insert(['id' => 1, 'product_name' => 'Cruise']);
        DB::table('destinations')->insert(['id' => 1, 'destination_name' => 'Caribbean']);
        DB::table('customers')->insert([
            ['id' => 1, 'fname' => 'First'],
            ['id' => 2, 'fname' => 'Second'],
            ['id' => 648, 'fname' => 'Excluded'],
        ]);
    }

    private function user(int $id = 1, int $role = 1): User
    {
        $user = new User;
        $user->id = $id;
        $user->role = $role;

        return $user;
    }

    private function report(string $name, array $filters = [], ?User $user = null): array
    {
        return (new SalesReports)->run($name, array_merge([
            'beginDate' => '2026-01-01',
            'endDate' => '2026-01-31',
            'dateBasis' => 'checkin',
        ], $filters), $user ?? $this->user());
    }

    private function reservation(array $values = []): int
    {
        return DB::table('reservations')->insertGetId(['agent_id' => 10] + $values);
    }

    public function test_product_reports_preserve_legacy_status_rules_and_enforce_agent_scope(): void
    {
        $this->reservation(['reservation_cost' => 100, 'status' => 'Active']);
        $this->reservation(['reservation_cost' => 200, 'status' => 'Canceled - Commission Protected']);
        $this->reservation(['reservation_cost' => 300, 'status' => 'Claimed']);
        $this->reservation(['reservation_cost' => 1000, 'is_deleted' => 1]);
        DB::table('reservations')->insert(['agent_id' => 20, 'reservation_cost' => 600]);
        DB::table('reservations')->insert(['agent_id' => 6680, 'reservation_cost' => 700]);

        foreach (['productSalesByAgentReport', 'agentSalesByProductReport'] as $name) {
            $sections = $this->report($name, ['agentId' => 20], $this->user(10, 2));
            $this->assertEquals(300, $sections[0]['totals']['total_sales']);
            $this->assertEquals($name === 'productSalesByAgentReport' ? 600 : 300, $sections[1]['totals']['total_sales']);
            $this->assertEquals(300, $sections[2]['totals']['total_sales']);
            $this->assertArrayNotHasKey('gross_commission', $sections[0]['columns']);
            $this->assertCount($name === 'productSalesByAgentReport' ? 1 : 2, $sections[2]['rows']);
            $this->assertSame('Amy', data_get($sections[2]['rows']->first(), 'agent'));
        }
    }

    public function test_total_sales_preserves_source_travel_totals_on_booking_tabs(): void
    {
        $this->reservation(['reservation_cost' => 100]);
        $this->reservation([
            'reservation_cost' => 250,
            'checkin_date' => '2026-02-01',
            'checkout_date' => '2026-02-10',
            'created_on' => '2026-01-31 23:59:59',
            'status' => 'Claimed',
        ]);
        $this->reservation(['reservation_cost' => 800, 'status' => 'Canceled - Commission Protected']);
        $this->reservation(['reservation_cost' => 900, 'status' => 'Prospect']);

        $this->assertEquals(100, $this->report('totalSalesReport')[0]['totals']['total_sales']);
        $this->assertEquals(100, $this->report('totalSalesReport', ['dateBasis' => 'book'])[0]['totals']['total_sales']);
        $this->assertEquals(100, $this->report('totalSalesReport', ['dateBasis' => 'both'])[0]['totals']['total_sales']);
    }

    public function test_aliases_preserve_deleted_sales_and_hide_opted_out_agents(): void
    {
        $this->reservation(['reservation_cost' => 150, 'agency_commission' => 30]);
        $this->reservation(['reservation_cost' => 999, 'is_deleted' => 1]);
        $this->reservation(['reservation_cost' => 500, 'status' => 'Canceled - Commission Protected']);
        DB::table('users')->where('id', 20)->update(['include_in_alias_reports' => 1]);
        DB::table('reservations')->insert(['agent_id' => 20, 'reservation_cost' => 2000]);

        $sales = $this->report('aliasTotalSalesReport')[0];
        $this->assertCount(1, $sales['rows']);
        $this->assertSame('Agent A', $sales['rows']->first()->alias);
        $this->assertEquals(1149, $sales['totals']['total_sales']);
        $this->assertEquals(50, $this->report('aliasTotalGrossCommission')[0]['totals']['gross_commission']);
        $this->assertCount(1, $this->report('aliasTotalSalesReport', [], $this->user(20, 2))[0]['rows']);
    }

    public function test_deposit_report_preserves_one_reservation_cost_per_deposit(): void
    {
        $id = $this->reservation(['reservation_cost' => 1200, 'checkin_date' => '2027-02-01']);
        DB::table('reservation_payments')->insert([
            ['reservation_id' => $id, 'payment_date' => '2026-01-02'],
            ['reservation_id' => $id, 'payment_date' => '2026-01-31'],
            ['reservation_id' => $id, 'payment_date' => '2026-02-01 12:00:00'],
        ]);
        DB::table('reservation_payments')->insert([
            'reservation_id' => $id, 'payment_date' => '2026-01-03 12:00:00', 'is_deleted' => 1,
        ]);
        $sections = $this->report('productSalesWithDepositByAgentReport');

        foreach ($sections as $section) {
            $this->assertEquals(2400, $section['totals']['total_sales']);
        }
        $this->assertCount(2, $sections[2]['rows']);
        $this->assertSame('2026-01-02', $sections[2]['rows']->first()->payment_date);
    }

    public function test_deposit_report_requires_an_admin_even_when_called_directly(): void
    {
        $this->expectException(HttpException::class);
        $this->report('productSalesWithDepositByAgentReport', [], $this->user(10, 2));
    }

    public function test_monthly_sales_preserve_different_header_and_full_month_chart_rules(): void
    {
        $this->reservation(['reservation_cost' => 100.75, 'checkin_date' => '2026-01-20']);
        $this->reservation(['reservation_cost' => 999, 'checkin_date' => '2026-01-02']);
        $this->reservation(['reservation_cost' => 888, 'status' => 'Canceled', 'checkin_date' => '2026-01-20']);
        DB::table('reservations')->insert(['agent_id' => 20, 'reservation_cost' => 2000]);

        $sections = $this->report('salesReport', [
            'beginDate' => '2026-01-15', 'endDate' => '2026-02-28', 'agentId' => 20,
        ], $this->user(10, 2));

        $this->assertEquals(988.75, $sections[0]['totals']['total_sales']);
        $this->assertEquals(1099, $sections[1]['totals']['total_sales']);
        $this->assertCount(2, $sections[1]['rows']);
        $this->assertEquals(0, $sections[1]['rows']->last()['total_sales']);
    }

    public function test_rebooking_uses_distinct_customers_and_year_bounds_with_agent_scope(): void
    {
        $this->reservation(['customer_id' => 1]);
        $this->reservation(['customer_id' => 1, 'checkin_date' => '2026-06-01', 'status' => 'Canceled - Commission Protected']);
        $this->reservation(['customer_id' => 2]);
        $this->reservation(['customer_id' => 648]);
        $this->reservation(['customer_id' => 2, 'status' => 'Active']);
        $this->reservation(['customer_id' => 2, 'is_deleted' => 1]);
        DB::table('reservations')->insert(['agent_id' => 20, 'customer_id' => 2]);

        $sections = $this->report('rebookingRateReport', [
            'beginYear' => 2026, 'endYear' => 2026, 'agentId' => 20,
        ], $this->user(10, 2));

        $this->assertCount(1, $sections[0]['rows']);
        $summary = $sections[0]['rows']->first();
        $this->assertEquals(2, $summary['customers']);
        $this->assertEquals(1, $summary['repeat_customers']);
        $this->assertEquals(2, $summary['reservations']);
        $this->assertEquals(50, $summary['rebooking_rate']);
        $this->assertCount(1, $sections[1]['rows']);
        $this->assertSame('First', $sections[1]['rows']->first()->customer);
    }

    public function test_all_sales_presentations_render_with_escaped_database_values(): void
    {
        DB::table('users')->where('id', 10)->update(['fname' => '<script>alert(1)</script>']);
        $id = $this->reservation();
        $this->reservation();
        DB::table('reservation_payments')->insert(['reservation_id' => $id, 'payment_date' => '2026-01-10']);

        foreach ([
            'productSalesByAgentReport', 'agentSalesByProductReport', 'totalSalesReport',
            'aliasTotalSalesReport', 'aliasTotalGrossCommission', 'productSalesWithDepositByAgentReport',
            'salesReport', 'rebookingRateReport',
        ] as $reportKey) {
            $html = view('reports.partials.salesData', [
                'sections' => $this->report($reportKey),
                'filters' => [],
                'reportKey' => $reportKey,
                'definition' => [],
            ])->render();

            $this->assertStringContainsString('sales-report', $html);
            $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
            if (! str_starts_with($reportKey, 'alias')) {
                $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
            }
        }
    }

    public function test_destination_search_retains_parent_cards_and_original_totals(): void
    {
        DB::table('products')->insert(['id' => 2, 'product_name' => 'Resort']);
        DB::table('destinations')->insert(['id' => 2, 'destination_name' => 'Alaska']);
        $this->reservation();
        $this->reservation(['product_id' => 2, 'destination_id' => 2, 'reservation_cost' => 250]);
        DB::table('reservations')->insert(['agent_id' => 20, 'product_id' => 2, 'destination_id' => 2, 'reservation_cost' => 500]);

        $sections = $this->report('productSalesByAgentReport', ['search' => 'Caribbean']);

        $this->assertCount(1, $sections[0]['rows']);
        $this->assertSame('Amy', data_get($sections[0]['rows']->first(), 'agent'));
        $this->assertEquals(350, data_get($sections[0]['rows']->first(), 'total_sales'));
        $this->assertEquals(850, $sections[0]['totals']['total_sales']);
        $this->assertCount(1, $sections[1]['rows']);
        $this->assertSame('Cruise', data_get($sections[1]['rows']->first(), 'product'));
        $this->assertCount(1, $sections[2]['rows']);
        $this->assertSame('Caribbean', data_get($sections[2]['rows']->first(), 'destination'));
    }

    public function test_customer_search_keeps_its_agent_and_sales_search_keeps_complete_charts(): void
    {
        $this->reservation(['customer_id' => 1]);
        $this->reservation(['customer_id' => 1]);
        $this->reservation(['customer_id' => 2]);
        $this->reservation(['customer_id' => 2]);
        $rebooking = $this->report('rebookingRateReport', ['search' => 'First'], $this->user(10, 2));

        $this->assertCount(1, $rebooking[0]['rows']);
        $this->assertEquals(4, data_get($rebooking[0]['rows']->first(), 'reservations'));
        $this->assertCount(1, $rebooking[1]['rows']);
        $this->assertSame('First', data_get($rebooking[1]['rows']->first(), 'customer'));

        $monthly = $this->report('salesReport', ['endDate' => '2026-02-28', 'search' => 'Amy'], $this->user(10, 2));
        $this->assertCount(1, $monthly[0]['rows']);
        $this->assertEquals(400, data_get($monthly[0]['rows']->first(), 'total_sales'));
        $this->assertCount(2, $monthly[1]['rows']);
        $this->assertSame('Feb, 2026', data_get($monthly[1]['rows']->last(), 'month'));

        $monthSearch = $this->report('salesReport', ['endDate' => '2026-02-28', 'search' => 'Feb'], $this->user(10, 2));
        $this->assertCount(0, $monthSearch[0]['rows']);
        $this->assertCount(0, $monthSearch[1]['rows']);
    }

    public function test_agents_see_the_anonymous_alias_leaderboard_without_real_names_or_ids(): void
    {
        $this->reservation(['reservation_cost' => 150, 'agency_commission' => 30]);
        DB::table('reservations')->insert(['agent_id' => 20, 'reservation_cost' => 300, 'agency_commission' => 60]);
        DB::table('users')->insert([
            'id' => 30, 'fname' => 'Hidden agent', 'alias' => 'Hidden alias', 'include_in_alias_reports' => 1,
        ]);
        DB::table('reservations')->insert(['agent_id' => 30, 'reservation_cost' => 900, 'agency_commission' => 180]);

        foreach (['aliasTotalSalesReport' => 'total_sales', 'aliasTotalGrossCommission' => 'gross_commission'] as $reportKey => $amountField) {
            $sections = $this->report($reportKey, ['agentId' => 20], $this->user(20, 2));
            $rows = $sections[0]['rows'];
            $this->assertSame(['Agent A', 'Agent B'], $rows->pluck('alias')->all());
            $this->assertEquals($amountField === 'total_sales' ? 450 : 90, $sections[0]['totals'][$amountField]);
            foreach ($rows as $row) {
                $this->assertSame(['alias', $amountField], array_keys((array) $row));
            }
            $html = view('reports.partials.salesData', [
                'sections' => $sections, 'filters' => [], 'reportKey' => $reportKey, 'definition' => [],
            ])->render();
            $this->assertStringContainsString('Agent A', $html);
            $this->assertStringContainsString('Agent B', $html);
            $this->assertStringNotContainsString('Amy', $html);
            $this->assertStringNotContainsString('Ben', $html);
            $this->assertStringNotContainsString('Hidden alias', $html);
        }
    }

    public function test_alias_rank_order_and_gross_commission_explanation_match_the_original(): void
    {
        $this->reservation(['reservation_cost' => 500, 'agency_commission' => 100]);
        DB::table('reservations')->insert(['agent_id' => 20, 'reservation_cost' => 100, 'agency_commission' => 20]);

        foreach (['aliasTotalSalesReport', 'aliasTotalGrossCommission'] as $reportKey) {
            $sections = $this->report($reportKey);
            $this->assertSame(['Agent B', 'Agent A'], $sections[0]['rows']->pluck('alias')->all());
            $html = view('reports.partials.salesData', compact('sections', 'reportKey'))->render();
            $this->assertStringContainsString('data-report-sort-column="1"', $html);
            if ($reportKey === 'aliasTotalGrossCommission') {
                $this->assertSame('Total Gross Commission', $sections[0]['columns']['gross_commission']);
                $this->assertStringContainsString('Meaning the full commission amount not the split.', $html);
            }
        }
    }

    public function test_total_sales_displays_the_source_total_row_and_plain_zero_placeholder(): void
    {
        $this->reservation(['reservation_cost' => 100]);
        $this->reservation(['reservation_cost' => 200]);
        $reportKey = 'totalSalesReport';
        $sections = $this->report($reportKey);
        $html = view('reports.partials.salesData', compact('sections', 'reportKey'))->render();

        $this->assertStringContainsString('<tfoot>', $html);
        $this->assertStringContainsString('Total:', $html);
        $this->assertStringContainsString('$300.00', $html);
        $this->assertStringContainsString('<td data-sort-value="0">0</td>', $html);
        $this->assertStringContainsString('data-report-sortable', $html);
    }

    public function test_rebooking_preserves_distinct_rate_and_missing_customer_display_queries(): void
    {
        $this->reservation(['customer_id' => 101]);
        $this->reservation(['customer_id' => 102]);
        $reportKey = 'rebookingRateReport';
        $sections = $this->report($reportKey);
        $summary = $sections[0]['rows']->first();

        $this->assertEquals(2, $summary['customers']);
        $this->assertEquals(0, $summary['repeat_customers']);
        $this->assertEquals(0, $summary['rebooking_rate']);
        $this->assertEquals(2, $summary['reservations']);
        $this->assertCount(1, $sections[1]['rows']);
        $this->assertNull($sections[1]['rows']->first()->customer_id);
        $html = view('reports.partials.salesData', compact('sections', 'reportKey'))->render();
        $this->assertStringContainsString('Total Reservations', $html);
        $this->assertStringNotContainsString('Rebooking Percentage', $html);
        $this->assertStringNotContainsString('data-report-sortable', $html);
    }

    public function test_sales_chart_uses_the_original_highcharts_series_and_a_fallback(): void
    {
        $this->reservation(['reservation_cost' => 100.75]);
        $reportKey = 'salesReport';
        $sections = $this->report($reportKey, [], $this->user(10, 2));
        $html = view('reports.partials.salesData', compact('sections', 'reportKey'))->render();

        $this->assertStringContainsString('https://code.highcharts.com/highcharts.js', $html);
        $this->assertStringContainsString("chart: { type: 'column' }", $html);
        $this->assertStringContainsString('data-sales-series="[{&quot;name&quot;:&quot;Jan, 2026&quot;,&quot;y&quot;:100}]"', $html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('Total Sales: $100.75', $html);
    }
}
