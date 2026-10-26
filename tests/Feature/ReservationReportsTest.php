<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Reports\ReservationReports;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReservationReportsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('id')->primary();
            foreach (['fname', 'mname', 'lname', 'email', 'state'] as $column) {
                $table->string($column)->nullable();
            }
            $table->integer('is_deleted')->default(0);
            $table->integer('agent_id')->default(1);
        });
        foreach (['products' => 'product_name', 'destinations' => 'destination_name', 'resort_ships' => 'resort_ship_name'] as $name => $label) {
            Schema::create($name, function (Blueprint $table) use ($name, $label) {
                $table->integer('id')->primary();
                $table->string($label);
                if ($name === 'products') {
                    $table->string('product_type')->nullable();
                    $table->integer('is_deleted')->default(0);
                }
            });
        }
        Schema::create('states', fn (Blueprint $table) => $table->string('name'));
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            foreach (['agent_id', 'customer_id', 'product_id', 'destination_id', 'resort_id', 'cruise_itinerary_id'] as $column) {
                $table->integer($column)->default(1);
            }
            $table->string('reservation_number')->default('RES');
            $table->string('reservation_name')->nullable();
            $table->text('reservation_notes')->nullable();
            $table->string('status')->default('Active');
            foreach (['checkin_date', 'checkout_date', 'created_on', 'unknown_reservation_date', 'last_modified_on'] as $column) {
                $table->string($column)->nullable();
            }
            $table->integer('last_modified_by')->nullable();
            foreach (['reservation_cost', 'agency_commission', 'agent_commission'] as $column) {
                $table->decimal($column, 12, 2)->nullable()->default(0);
            }
            foreach (['is_deleted', 'commission_claimed', 'unknown_reservation_checked_flag', 'is_luxury_magazine_lead',
                'is_facebook_lead', 'is_website_lead', 'is_website_lead_knot', 'is_virtuoso_lead',
                'is_instagram_lead', 'radio_station_ads'] as $column) {
                $table->integer($column)->nullable()->default(0);
            }
        });
        Schema::create('reservation_paid_in_full_audit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id');
            $table->string('field_name');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->integer('modified_by');
            $table->string('modified_on');
        });
        DB::table('users')->insert([
            ['id' => 1, 'fname' => 'Admin', 'lname' => 'One'],
            ['id' => 2, 'fname' => 'Agent', 'lname' => 'Two'],
        ]);
        DB::table('customers')->insert(['id' => 1, 'fname' => 'Test', 'lname' => 'Customer', 'email' => 'customer@example.test', 'state' => 'Ohio']);
        DB::table('products')->insert(['id' => 1, 'product_name' => 'Test Cruise', 'product_type' => 'Cruise']);
        DB::table('destinations')->insert(['id' => 1, 'destination_name' => 'Test Destination']);
        DB::table('resort_ships')->insert(['id' => 1, 'resort_ship_name' => 'Test Resort']);
        DB::table('states')->insert(['name' => 'Ohio']);
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        parent::tearDown();
    }

    private function user(int $id = 1, int $role = 1): User
    {
        return (new User)->forceFill(['id' => $id, 'role' => $role]);
    }

    private function reservation(array $attributes = []): int
    {
        return DB::table('reservations')->insertGetId($attributes + [
            'reservation_number' => 'RES',
            'checkin_date' => '2026-01-10',
            'checkout_date' => '2026-01-20',
            'created_on' => '2025-12-31 23:59:59',
            'reservation_cost' => 1000,
            'agency_commission' => 100,
            'agent_commission' => 70,
        ]);
    }

    private function report(string $name, array $filters = [], ?User $user = null): array
    {
        return (new ReservationReports)->run($name, $filters + [
            'beginDate' => '2026-01-01', 'endDate' => '2026-01-31', 'year' => 2026,
        ], $user ?? $this->user());
    }

    public function test_travel_reports_preserve_legacy_inclusive_date_only_bounds(): void
    {
        $this->reservation(['reservation_number' => 'BOUNDARY', 'checkout_date' => '2026-01-31']);
        $this->reservation(['reservation_number' => 'AFTER_END_DATE', 'checkout_date' => '2026-01-31 23:59:59']);
        $this->reservation(['reservation_number' => 'OVERLAP', 'checkout_date' => '2026-02-01']);
        $this->reservation(['reservation_number' => 'BEFORE', 'checkin_date' => '2025-12-31']);
        $this->reservation(['reservation_number' => 'DELETED', 'is_deleted' => 1]);
        $this->reservation(['reservation_number' => 'EXCLUDED', 'agent_id' => 6680]);

        $sections = $this->report('allReservationsByDateReport');

        $this->assertSame(['BOUNDARY'], $sections[0]['rows']->pluck('reservation_number')->all());
    }

    public function test_all_trips_always_uses_signed_in_user_and_paid_in_full_status(): void
    {
        $this->reservation(['reservation_number' => 'OWN', 'status' => 'Paid in Full']);
        $this->reservation(['reservation_number' => 'OTHER', 'agent_id' => 2, 'status' => 'Paid in Full']);
        $this->reservation(['reservation_number' => 'ACTIVE', 'status' => 'Active']);

        $sections = $this->report('allTripsByTravelDateReport', ['agentId' => 2]);

        $this->assertSame(['OWN'], $sections[0]['rows']->pluck('reservation_number')->all());
        $this->assertSame('customer@example.test', $sections[0]['rows'][0]->customer_email);
    }

    public function test_expensive_trips_are_ordered_limited_and_scope_cannot_be_overridden(): void
    {
        foreach (range(1, 17) as $number) {
            $this->reservation(['reservation_number' => 'R'.$number, 'status' => 'Paid in Full', 'reservation_cost' => $number * 100]);
        }
        $this->reservation(['reservation_number' => 'OTHER', 'status' => 'Paid in Full', 'reservation_cost' => 99999, 'agent_id' => 2]);
        $this->reservation(['reservation_number' => 'ACTIVE', 'status' => 'Active', 'reservation_cost' => 99999]);

        $sections = $this->report('top15ExpensiveTripsReport', ['agentId' => 2], $this->user(1, 2));

        $this->assertCount(15, $sections[0]['rows']);
        $this->assertSame('R17', $sections[0]['rows']->first()->reservation_number);
        $this->assertSame('R3', $sections[0]['rows']->last()->reservation_number);
    }

    public function test_lead_summary_preserves_multiple_sources_qualifying_statuses_and_radio_ads(): void
    {
        $this->reservation(['is_facebook_lead' => 1, 'is_website_lead' => 1]);
        $this->reservation(['status' => 'Canceled w/ Insurance Payout', 'radio_station_ads' => 1, 'reservation_cost' => 200]);
        $this->reservation(['status' => 'Canceled', 'is_facebook_lead' => 1, 'reservation_cost' => 50000]);
        $this->reservation(['agent_id' => 6680, 'is_facebook_lead' => 1, 'reservation_cost' => 50000]);
        $this->reservation(['agent_id' => 2, 'is_facebook_lead' => 1, 'reservation_cost' => 50000]);

        $sections = $this->report('resleadsReport', ['agentId' => 2], $this->user(1, 2));
        $sources = $sections[0]['rows']->keyBy('source');

        $this->assertEquals(1, $sources['Facebook Lead']->reservations);
        $this->assertEquals(1, $sources['Website Lead']->reservations);
        $this->assertEquals(200, $sources['Radio Lead']->reservation_cost);
        $this->assertEquals(2200, $sections[0]['totals']['reservation_cost']);
    }

    public function test_unknown_monthly_totals_distinguish_claimed_and_checked_amounts(): void
    {
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2020-05-01', 'agency_commission' => 100]);
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2020-05-01', 'agency_commission' => 200, 'commission_claimed' => 1]);
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2020-05-01', 'agency_commission' => 300, 'unknown_reservation_checked_flag' => 1]);
        $this->reservation(['agent_id' => 2, 'unknown_reservation_date' => '2020-05-01', 'agency_commission' => 9000]);

        $sections = $this->report('unknownReservationsReport');

        $this->assertEquals(400, $sections[0]['totals']['agency_commission']);
        $this->assertSame('May 2020', $sections[0]['rows'][0]['month']);
        $this->assertEquals(100, $sections[2]['rows'][0]['agency_commission']);
        $this->assertEquals(200, $sections[2]['rows'][1]['agency_commission']);
    }

    public function test_website_knot_drilldown_preserves_legacy_active_reservation_exclusion(): void
    {
        $this->reservation(['is_website_lead_knot' => 1, 'status' => 'Active']);
        $this->reservation(['is_website_lead_knot' => 1, 'status' => 'Paid in Full']);

        $sections = $this->report('resleadsReport');
        $summary = $sections[0]['rows']->keyBy('source');
        $detail = collect($sections)->firstWhere('title', 'Website Knot Lead');

        $this->assertEquals(2, $summary['Website Knot Lead']->reservations);
        $this->assertCount(1, $detail['rows']);
        $this->assertSame('Paid in Full', $detail['rows'][0]->status);
    }

    public function test_monthly_report_preserves_legacy_month_cutoff_and_date_only_timestamp_bounds(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-15'));
        $this->reservation(['created_on' => '2025-12-31 23:59:59', 'checkout_date' => '2025-12-31']);
        $this->reservation(['created_on' => '2025-01-31 23:59:59', 'checkout_date' => '2025-01-31']);
        $this->reservation(['agent_id' => 6680, 'created_on' => '2025-12-01', 'checkout_date' => '2025-12-20']);

        $sections = $this->report('bookedReservationsPerMonthReport', ['year' => 2025]);
        $rows = $sections[0]['rows'];

        $this->assertCount(12, $rows);
        $this->assertSame(0, $rows[11]['booked']);
        $this->assertSame(0, $rows[11]['traveled']);
        $this->assertSame(0, $rows[0]['booked']);
        $this->assertSame(1, $rows[0]['traveled']);
        $this->assertSame(1, $rows[5]['traveled']);
    }

    public function test_recorded_changes_use_audit_date_and_agent_ownership(): void
    {
        $own = $this->reservation(['agent_id' => 2, 'checkin_date' => '2020-01-01']);
        $other = $this->reservation(['agent_id' => 1]);
        foreach ([$own, $other] as $id) {
            DB::table('reservation_paid_in_full_audit')->insert([
                'reservation_id' => $id, 'field_name' => 'reservation_cost',
                'old_value' => '100', 'new_value' => '200',
                'modified_by' => 1, 'modified_on' => '2026-01-31 23:59:59',
            ]);
        }

        $sections = $this->report('reservationsChangesReport', [], $this->user(2, 2));

        $this->assertCount(1, $sections[0]['rows']);
        $this->assertSame('Admin One', $sections[0]['rows'][0]->modified_by);
        $this->assertSame('200', $sections[0]['rows'][0]->new_value);
    }

    public function test_state_totals_preserve_legacy_status_and_customer_detail_rules(): void
    {
        DB::table('customers')->insert(['id' => 645, 'fname' => 'Excluded', 'lname' => 'Detail', 'state' => 'Ohio']);
        $this->reservation();
        $this->reservation(['customer_id' => 645, 'reservation_cost' => 500]);
        $this->reservation(['status' => 'Canceled w/ Insurance Payout', 'reservation_cost' => 7000]);
        $this->reservation(['status' => 'Canceled - Commission Protected', 'reservation_cost' => 200]);

        $sections = $this->report('bookedTripsByStateReport', ['state' => 'Ohio']);

        $this->assertEquals(1700, $sections[0]['totals']['reservation_cost']);
        $this->assertCount(2, $sections[1]['rows']);
        $this->assertEquals(1700, $sections[1]['summary']['reservation_cost']);
    }

    public function test_supplier_report_returns_distinct_resorts_without_a_status_filter(): void
    {
        $this->reservation(['product_id' => 32, 'status' => 'Canceled']);
        $this->reservation(['product_id' => 32, 'status' => 'Active']);
        $this->reservation(['product_id' => 19, 'resort_id' => 2]);
        $this->reservation(['product_id' => 32, 'resort_id' => 2, 'agent_id' => 6680]);
        DB::table('resort_ships')->insert(['id' => 2, 'resort_ship_name' => 'Excluded Resort']);

        $sections = $this->report('hotelOnlyReport');

        $this->assertSame(['Test Resort'], $sections[0]['rows']->pluck('resort_ship_name')->all());
        $this->assertSame(['resort_ship_name' => 'Hotel Only'], $sections[0]['columns']);
    }

    public function test_grouped_report_views_render_totals_details_and_escape_customer_names(): void
    {
        DB::table('customers')->where('id', 1)->update(['fname' => '<script>unsafe</script>']);
        $this->reservation(['is_facebook_lead' => 1]);
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-01-01', 'commission_claimed' => 1]);

        foreach (['bookedTripsByStateReport', 'unknownReservationsReport', 'resleadsReport'] as $reportKey) {
            $html = view('reports.partials.reservationData', [
                'sections' => $this->report($reportKey),
                'filters' => [],
                'reportKey' => $reportKey,
                'definition' => ['title' => 'Report'],
            ])->render();

            $this->assertStringContainsString('$100.00', $html);
            $this->assertStringNotContainsString('<script>unsafe</script>', $html);
            if ($reportKey !== 'unknownReservationsReport') {
                $this->assertStringContainsString('&lt;script&gt;unsafe&lt;/script&gt;', $html);
            }
        }
    }

    public function test_unknown_report_preserves_exact_date_groups_and_first_day_drilldowns(): void
    {
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-05-01', 'agency_commission' => 100]);
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-05-15', 'agency_commission' => 300]);
        $this->reservation(['agent_id' => 0, 'customer_id' => 9000, 'unknown_reservation_date' => '2026-05-01', 'agency_commission' => 50]);

        $sections = $this->report('unknownReservationsReport');

        $this->assertCount(2, $sections[0]['rows']);
        $this->assertEquals(150, $sections[0]['rows'][0]['agency_commission']);
        $this->assertEquals(300, $sections[0]['rows'][1]['agency_commission']);
        $this->assertCount(1, $sections[1]['rows']);
        $this->assertCount(1, $sections[2]['rows']);
        $this->assertEquals(100, $sections[2]['totals']['agency_commission']);
    }

    public function test_admin_can_check_and_uncheck_an_unknown_reservation(): void
    {
        $id = $this->reservation(['agent_id' => 0]);

        $this->actingAs($this->user())->post(route('reports.unknownReservationsReport.checked'), [
            'reservation_id' => $id, 'checked' => 1, 'search' => 'RES',
        ])->assertRedirect(route('reports.unknownReservationsReport', ['search' => 'RES']));
        $this->assertDatabaseHas('reservations', [
            'id' => $id, 'unknown_reservation_checked_flag' => 1, 'last_modified_by' => 1,
        ]);
        $this->assertNotNull(DB::table('reservations')->where('id', $id)->value('last_modified_on'));

        $this->post(route('reports.unknownReservationsReport.checked'), [
            'reservation_id' => $id, 'checked' => 0,
        ])->assertRedirect(route('reports.unknownReservationsReport'));
        $this->assertDatabaseHas('reservations', ['id' => $id, 'unknown_reservation_checked_flag' => 0]);
    }

    public function test_agents_cannot_update_unknown_checked_flags(): void
    {
        $id = $this->reservation(['agent_id' => 0]);

        $this->actingAs($this->user(2, 2))->postJson(route('reports.unknownReservationsReport.checked'), [
            'reservation_id' => $id, 'checked' => 1,
        ])->assertForbidden();

        $this->assertDatabaseHas('reservations', ['id' => $id, 'unknown_reservation_checked_flag' => 0]);
    }

    public function test_checked_endpoint_rejects_assigned_deleted_and_missing_reservations(): void
    {
        $assigned = $this->reservation(['agent_id' => 1]);
        $deleted = $this->reservation(['agent_id' => 0, 'is_deleted' => 1]);
        $this->actingAs($this->user());

        foreach ([$assigned, $deleted, 999999] as $id) {
            $this->postJson(route('reports.unknownReservationsReport.checked'), [
                'reservation_id' => $id, 'checked' => 1,
            ])->assertNotFound();
        }
        $this->assertSame(0, DB::table('reservations')->where('unknown_reservation_checked_flag', 1)->count());
    }

    public function test_unknown_footer_displays_zero_for_empty_legacy_sums(): void
    {
        $this->reservation(['agent_id' => 0, 'unknown_reservation_checked_flag' => 1, 'commission_claimed' => 1]);

        $sections = $this->report('unknownReservationsReport');

        $this->assertEquals(['unclaimed' => 0, 'claimed' => 0], $sections[count($sections) - 1]['summary']);
    }

    public function test_unknown_sums_do_not_treat_null_flags_as_sql_zero(): void
    {
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-01-01', 'agency_commission' => 100]);
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-01-01', 'agency_commission' => 200, 'commission_claimed' => null]);
        $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-01-01', 'agency_commission' => 300, 'unknown_reservation_checked_flag' => null]);

        $sections = $this->report('unknownReservationsReport');

        $this->assertEquals(400, $sections[0]['totals']['agency_commission']);
        $this->assertEquals(100, $sections[count($sections) - 1]['summary']['unclaimed']);
    }

    public function test_unknown_editor_returns_original_fields_and_eligible_dropdown_options(): void
    {
        $id = $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-01-01', 'reservation_notes' => 'Test notes']);
        DB::table('customers')->insert([
            ['id' => 2, 'fname' => 'Unassigned', 'agent_id' => 0, 'is_deleted' => 0],
            ['id' => 3, 'fname' => 'Deleted', 'agent_id' => 1, 'is_deleted' => 1],
        ]);
        DB::table('products')->insert([
            ['id' => 2, 'product_name' => 'Alpha', 'is_deleted' => 0],
            ['id' => 3, 'product_name' => 'Priority', 'is_deleted' => 0],
            ['id' => 4, 'product_name' => 'Deleted', 'is_deleted' => 1],
        ]);

        $response = $this->actingAs($this->user())->getJson(route('reports.unknownReservationsReport.edit', $id))
            ->assertOk()->assertJsonPath('reservation.id', $id)
            ->assertJsonPath('reservation.reservation_notes', 'Test notes')
            ->assertJsonPath('reservation.month', '2026-01')
            ->assertJsonPath('reservation.agency_commission', '100.00')
            ->assertJsonCount(1, 'customers')
            ->assertJsonPath('update_url', route('reports.unknownReservationsReport.update', $id));

        $this->assertSame([1, 3, 2], array_column($response->json('products'), 'id'));
    }

    public function test_unknown_editor_updates_only_legacy_fields_and_audit_metadata(): void
    {
        $id = $this->reservation(['agent_id' => 0, 'unknown_reservation_date' => '2026-01-01']);

        $this->actingAs($this->user())->putJson(route('reports.unknownReservationsReport.update', $id), [
            'customer_id' => 1, 'product_id' => 1, 'reservation_number' => 'UPDATED',
            'agency_commission' => '12.30', 'month' => '2026-05', 'reservation_notes' => 'Updated notes',
            'agent_id' => 99, 'reservation_cost' => 50000,
        ])->assertOk()->assertJson(['saved' => true]);

        $this->assertDatabaseHas('reservations', [
            'id' => $id, 'agent_id' => 0, 'reservation_number' => 'UPDATED',
            'agency_commission' => 12.30, 'unknown_reservation_date' => '2026-05-01',
            'reservation_notes' => 'Updated notes', 'last_modified_by' => 1,
            'reservation_cost' => 1000, 'status' => 'Active',
        ]);
    }

    public function test_unknown_editor_allows_optional_product_and_commission_but_validates_customer_and_month(): void
    {
        $id = $this->reservation(['agent_id' => 0]);
        $this->actingAs($this->user());
        $payload = ['customer_id' => 1, 'product_id' => -1, 'reservation_number' => 'RES', 'agency_commission' => null, 'month' => '2026-05'];

        $this->putJson(route('reports.unknownReservationsReport.update', $id), $payload)->assertOk();
        $this->assertDatabaseHas('reservations', ['id' => $id, 'product_id' => -1, 'agency_commission' => null]);

        $this->putJson(route('reports.unknownReservationsReport.update', $id), array_replace($payload, [
            'customer_id' => 999999, 'month' => 'invalid',
        ]))->assertUnprocessable()->assertJsonValidationErrors(['customer_id', 'month']);
    }

    public function test_unknown_editor_denies_agents_and_assigned_or_deleted_records(): void
    {
        $unknown = $this->reservation(['agent_id' => 0]);
        $assigned = $this->reservation(['agent_id' => 1]);
        $deleted = $this->reservation(['agent_id' => 0, 'is_deleted' => 1]);
        $this->actingAs($this->user(2, 2));
        $this->getJson(route('reports.unknownReservationsReport.edit', $unknown))->assertForbidden();
        $this->putJson(route('reports.unknownReservationsReport.update', $unknown), [])->assertForbidden();

        $this->actingAs($this->user());
        foreach ([$assigned, $deleted, 999999] as $id) {
            $this->getJson(route('reports.unknownReservationsReport.edit', $id))->assertNotFound();
            $this->putJson(route('reports.unknownReservationsReport.update', $id), [])->assertNotFound();
        }
    }

    public function test_inline_unknown_checkbox_returns_updated_totals_without_navigation(): void
    {
        $id = $this->reservation(['agent_id' => 0, 'agency_commission' => 100]);
        $this->reservation(['agent_id' => 0, 'agency_commission' => 200]);
        $this->reservation(['agent_id' => 0, 'agency_commission' => 300, 'commission_claimed' => 1]);

        $this->actingAs($this->user())->postJson(route('reports.unknownReservationsReport.checked'), [
            'reservation_id' => $id, 'checked' => 1,
        ])->assertOk()->assertJson(['checked' => true, 'unchecked_total' => 200, 'claimed_total' => 300]);
    }
}
