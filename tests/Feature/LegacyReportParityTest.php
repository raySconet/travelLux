<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LegacyReportParityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->primary();
            foreach (['fname', 'lname', 'first_address_line', 'city', 'state', 'cell_phone_number', 'birth_date', 'ssn', 'ein', 'postal_code'] as $field) {
                $table->string($field)->default('');
            }
            $table->integer('isDeleted')->default(0);
            $table->integer('is_disabled')->default(-1);
        });
        Schema::create('reservations', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('agent_id')->default(8);
            $table->integer('customer_id')->default(1);
            $table->integer('product_id')->default(1);
            $table->integer('destination_id')->default(1);
            $table->integer('resort_id')->default(-1);
            $table->string('reservation_number')->default('RES-1');
            $table->string('status')->default('Paid in Full');
            $table->date('checkin_date')->default('2025-03-01');
            $table->date('checkout_date')->default('2025-03-10');
            $table->integer('commission_received')->default(1);
            $table->integer('agent_commission_received')->default(0);
            $table->integer('non_commissionable')->default(0);
            $table->integer('is_deleted')->default(0);
            $table->decimal('reservation_cost')->default(1000);
            $table->decimal('agency_commission')->default(200);
            $table->decimal('agent_commission')->default(80);
            $table->integer('agent_commission_percentage')->default(40);
        });
        Schema::create('paid_commissions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('agent_id');
            $table->integer('reservation_id');
            $table->string('check_number')->default('CHECK1');
            $table->date('check_date')->default('2025-03-15');
            $table->decimal('amount');
        });
        foreach (['customers' => ['fname', 'lname'], 'products' => ['product_name'], 'destinations' => ['destination_name'], 'resort_ships' => ['resort_ship_name']] as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns) {
                $table->integer('id')->primary();
                foreach ($columns as $column) {
                    $table->string($column);
                }
            });
        }
        DB::table('users')->insert([
            ['id' => 8, 'fname' => 'Owner', 'lname' => 'Able', 'isDeleted' => 0],
            ['id' => 9, 'fname' => 'Historical', 'lname' => 'Baker', 'isDeleted' => 1],
            ['id' => 10, 'fname' => 'Current', 'lname' => 'Clark', 'isDeleted' => 0],
            ['id' => 6669, 'fname' => 'Internal', 'lname' => 'One', 'isDeleted' => 0],
            ['id' => 6680, 'fname' => 'Excluded', 'lname' => 'Two', 'isDeleted' => 0],
        ]);
        DB::table('customers')->insert(['id' => 1, 'fname' => 'Test', 'lname' => 'Customer']);
        DB::table('products')->insert(['id' => 1, 'product_name' => 'Vendor']);
        DB::table('destinations')->insert(['id' => 1, 'destination_name' => 'Destination']);
        $this->actingAs($this->actor());
    }

    private function actor(int $id = 1, int $role = 1): User
    {
        $user = new User(['role' => $role, 'fname' => 'Test', 'lname' => 'User']);
        $user->id = $id;

        return $user;
    }

    private function dates(): array
    {
        return ['beginDate' => '2025-01-01', 'endDate' => '2025-12-31'];
    }

    private function taxFixture(): void
    {
        DB::table('reservations')->insert([
            ['id' => 1, 'agency_commission' => 200],
            ['id' => 2, 'agency_commission' => 500],
        ]);
        DB::table('paid_commissions')->insert([
            ['id' => 1, 'agent_id' => 8, 'reservation_id' => 1, 'amount' => 80],
            ['id' => 2, 'agent_id' => 9, 'reservation_id' => 2, 'amount' => 100],
            ['id' => 3, 'agent_id' => 9, 'reservation_id' => 2, 'amount' => 20],
            ['id' => 4, 'agent_id' => 6669, 'reservation_id' => 1, 'amount' => 900],
            ['id' => 5, 'agent_id' => 6680, 'reservation_id' => 1, 'amount' => 900],
        ]);
    }

    public function test_1099_matches_exclusions_historical_agents_and_separate_agency_row(): void
    {
        $this->taxFixture();
        $this->postJson('/1099Report/load', $this->dates())->assertOk()
            ->assertJsonPath('totalPaid', '200.00')->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.agent', 'Owner Able')->assertJsonPath('data.0.commission_paid', '80.00')
            ->assertJsonPath('data.1.agent', 'Owner Able (Agency)')->assertJsonPath('data.1.commission_paid', '1,000.00')
            ->assertJsonPath('data.2.agent', 'Historical Baker')->assertJsonPath('data.2.commission_paid', '120.00');
    }

    public function test_1099_agent_scope_keeps_original_owner_agency_calculation(): void
    {
        $this->taxFixture();
        $this->actingAs($this->actor(9, 2))->postJson('/1099Report/load', [...$this->dates(), 'agentId' => 8])
            ->assertJsonCount(1, 'data')->assertJsonPath('totalPaid', '120.00');
        $this->actingAs($this->actor(8, 2))->postJson('/1099Report/load', $this->dates())
            ->assertJsonCount(2, 'data')->assertJsonPath('data.1.commission_paid', '1,000.00');
    }

    public function test_1099_missing_user_and_blank_birth_date_do_not_crash(): void
    {
        DB::table('paid_commissions')->insert(['id' => 1, 'agent_id' => 999, 'reservation_id' => 999, 'amount' => 12.50]);
        $this->postJson('/1099Report/load', $this->dates())->assertOk()
            ->assertJsonPath('data.0.birth_date', '')->assertJsonPath('totalPaid', '12.50');
    }

    public function test_1099_invalid_ranges_and_empty_results(): void
    {
        $this->postJson('/1099Report/load', ['beginDate' => 'bad'])->assertUnprocessable();
        $this->postJson('/1099Report/load', ['beginDate' => '2025-12-31', 'endDate' => '2025-01-01'])->assertUnprocessable();
        $this->postJson('/1099Report/load', $this->dates())->assertOk()->assertJsonPath('success', false);
    }

    public function test_vendor_excludes_internal_accounts_and_missing_products_but_keeps_parent_totals(): void
    {
        DB::table('reservations')->insert([
            ['id' => 1, 'agent_id' => 8, 'product_id' => 1, 'destination_id' => 1],
            ['id' => 2, 'agent_id' => 6680, 'product_id' => 1, 'destination_id' => 1],
            ['id' => 3, 'agent_id' => 8, 'product_id' => 999, 'destination_id' => 1],
            ['id' => 4, 'agent_id' => 8, 'product_id' => 1, 'destination_id' => 999],
        ]);
        $response = $this->post('/vendorReport/load', $this->dates())->assertOk();
        $this->assertEquals(2000, $response->viewData('totalSales'));
        $this->assertEquals(160, $response->viewData('totalAgentCommission'));
        $response->assertSee('N/A');
    }

    public function test_current_checks_filters_inactive_agents_and_internal_account(): void
    {
        DB::table('users')->where('id', 10)->update(['is_disabled' => 1]);
        foreach ([8, 9, 10, 6680] as $id) {
            DB::table('reservations')->insert(['id' => $id, 'agent_id' => $id, 'reservation_number' => 'RES-'.$id]);
        }
        $this->get('/currentChecksReport/load')->assertOk()->assertSee('RES-8')
            ->assertDontSee('RES-9')->assertDontSee('RES-10')->assertDontSee('RES-6680');
    }

    public function test_check_history_owner_agency_total_uses_travel_dates_including_deleted_rows(): void
    {
        DB::table('reservations')->insert([
            ['id' => 1, 'agent_id' => 8, 'agent_commission_received' => 1, 'is_deleted' => 1],
            ['id' => 2, 'agent_id' => 6680, 'agent_commission_received' => 1, 'is_deleted' => 0],
        ]);
        DB::table('paid_commissions')->insert([
            ['id' => 1, 'agent_id' => 8, 'reservation_id' => 1, 'amount' => 80],
            ['id' => 2, 'agent_id' => 6680, 'reservation_id' => 2, 'amount' => 80],
        ]);
        $response = $this->post('/checkHistoryReport/load', $this->dates())->assertOk()->assertSee('Agency Total');
        $agents = $response->viewData('agents');
        $this->assertCount(1, $agents);
        $this->assertEquals(120, $agents->first()->agencyTotal);
        $this->assertEquals(80, $agents->first()->totalPaid);
    }
}
