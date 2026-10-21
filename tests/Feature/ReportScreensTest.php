<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Reports\FinancialReports;
use App\Services\Reports\ReportCatalog;
use App\Services\Reports\ReservationReports;
use App\Services\Reports\SalesReports;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReportScreensTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('fname');
            $table->string('lname');
            $table->integer('isDeleted')->default(0);
            $table->integer('is_disabled')->default(-1);
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('user_id');
            $table->integer('is_read');
        });
        Schema::create('states', function (Blueprint $table) {
            $table->string('name');
        });
        Schema::create('reservations', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('reservation_number');
            $table->integer('agent_id');
            $table->integer('is_deleted')->default(0);
        });
    }

    private function user(int $role = 1): User
    {
        $user = new User(['fname' => 'Test', 'lname' => 'Agent', 'role' => $role, 'email' => 'agent@example.test']);
        $user->id = 10;

        return $user;
    }

    private function path(string $key): string
    {
        return '/'.match ($key) {
            'resleadsReport' => 'reservationsLeadReport',
            default => $key,
        };
    }

    public function test_every_report_link_renders_its_screen_and_download_link(): void
    {
        foreach ([SalesReports::class, ReservationReports::class, FinancialReports::class] as $service) {
            $this->mock($service)->shouldReceive('run')->andReturn([]);
        }
        $this->actingAs($this->user());
        foreach (ReportCatalog::all() as $key => $definition) {
            $this->get($this->path($key))
                ->assertOk()->assertSee($definition[0])
                ->assertSee('reportDataContainer')->assertSee('No Data Available');
            if ($key !== 'salesReport') {
                $this->get($this->path($key))->assertSee('data-report-print', false);
            }
        }
    }

    public function test_admin_reports_and_their_downloads_are_forbidden_to_agents(): void
    {
        $this->actingAs($this->user(2));
        foreach (ReportCatalog::all() as $key => $definition) {
            if ($definition['admin'] ?? false) {
                $this->get($this->path($key))->assertForbidden();
                $this->get($this->path($key).'?export=csv')->assertForbidden();
            }
        }
    }

    public function test_report_links_require_authentication(): void
    {
        $this->get('/totalSalesReport')->assertRedirect('/login');
    }

    public function test_invalid_date_and_year_ranges_are_rejected_before_querying(): void
    {
        $this->actingAs($this->user());
        $this->getJson('/totalSalesReport?beginDate=2026-05-01&endDate=2026-04-01')
            ->assertUnprocessable()->assertJsonValidationErrors('endDate');
        $this->getJson('/totalSalesReport?beginDate=invalid')
            ->assertUnprocessable()->assertJsonValidationErrors('beginDate');
        $this->getJson('/rebookingRateReport?beginYear=2026&endYear=2025')
            ->assertUnprocessable()->assertJsonValidationErrors('endYear');
        $this->getJson('/totalSalesReport?dateBasis=arbitrary_sql')
            ->assertUnprocessable()->assertJsonValidationErrors('dateBasis');
    }

    public function test_agent_filter_cannot_override_the_signed_in_agent(): void
    {
        $user = $this->user(2);
        $user->id = 10;
        $this->mock(SalesReports::class)->shouldReceive('run')->once()
            ->withArgs(fn ($key, $filters, $actor) => $key === 'totalSalesReport' && $filters['agentId'] === 10 && $actor->id === 10)
            ->andReturn([]);
        $this->actingAs($user)->get('/totalSalesReport?agentId=20')->assertOk();
    }

    public function test_csv_download_preserves_money_and_escapes_spreadsheet_formulas(): void
    {
        $this->mock(SalesReports::class)->shouldReceive('run')->once()->andReturn([[
            'title' => 'Total Sales',
            'columns' => ['agent' => 'Agent', 'total_sales' => 'Sales'],
            'rows' => [['agent' => '=1+1', 'total_sales' => 1234.56]],
            'formats' => ['total_sales' => 'money'],
            'totals' => ['total_sales' => 1234.56],
        ]]);
        $response = $this->actingAs($this->user())->get('/totalSalesReport?export=csv');
        $response->assertOk()->assertDownload();
        $csv = $response->streamedContent();
        $this->assertStringContainsString("'=1+1", $csv);
        $this->assertStringContainsString('$1,234.56', $csv);
    }

    public function test_displayed_values_are_escaped_and_totals_keep_cents(): void
    {
        $this->mock(SalesReports::class)->shouldReceive('run')->andReturn([[
            'title' => 'Total Sales',
            'columns' => ['agent' => 'Agent', 'total_sales' => 'Sales'],
            'rows' => [
                ['agent' => '<script>alert(1)</script>', 'total_sales' => 10.5],
                ['agent' => 'Second agent', 'total_sales' => 20],
            ],
            'formats' => ['total_sales' => 'money'],
            'totals' => ['total_sales' => 30.5],
        ]]);
        $this->actingAs($this->user())->get('/totalSalesReport')
            ->assertOk()->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('Second agent')->assertSee('$10.50');
    }
}
