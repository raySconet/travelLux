<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AgentsReportTest extends TestCase
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
            $table->string('lname')->default('Agent');
            $table->string('email')->default('example@example.test');
            $table->string('city')->default('Miami');
            $table->string('state')->default('FL');
            $table->string('first_address_line')->default('123 Main Street');
            $table->string('cell_phone_number')->default('0012345678');
            $table->integer('isDeleted')->default(0);
            $table->integer('is_disabled')->default(-1);
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('user_id');
            $table->integer('is_read');
        });

        DB::table('users')->insert([
            ['id' => 10, 'fname' => 'Included Agent', 'is_disabled' => -1, 'isDeleted' => 0],
            ['id' => 20, 'fname' => 'Zero Flag Agent', 'is_disabled' => 0, 'isDeleted' => 0],
            ['id' => 30, 'fname' => 'Disabled Agent', 'is_disabled' => 1, 'isDeleted' => 0],
            ['id' => 40, 'fname' => 'Deleted Agent', 'is_disabled' => -1, 'isDeleted' => 1],
            ['id' => 6680, 'fname' => 'Excluded Legacy Agent', 'is_disabled' => -1, 'isDeleted' => 0],
        ]);
    }

    private function user(int $role = 1): User
    {
        $user = new User(['fname' => 'Administrator', 'lname' => 'Test', 'email' => 'admin@example.test', 'role' => $role]);
        $user->id = 900;

        return $user;
    }

    public function test_agents_report_matches_source_eligibility_columns_and_controls(): void
    {
        $response = $this->actingAs($this->user())->get('/agentsReport');

        $response->assertOk()->assertViewHas('users', fn ($users) => $users->pluck('fname')->all() === ['Included Agent'])
            ->assertSee('Included Agent')->assertSee('Miami - FL - 123 Main Street')
            ->assertSee('0012345678')->assertSee('data-report-print', false)
            ->assertSee('export=excel', false)->assertDontSee('Commission %')
            ->assertDontSee('Zero Flag Agent')->assertDontSee('Disabled Agent')
            ->assertDontSee('Deleted Agent')->assertDontSee('Excluded Legacy Agent')
            ->assertDontSee('data-report-sortable', false);

        $document = new \DOMDocument;
        @$document->loadHTML($response->getContent());
        $headers = (new \DOMXPath($document))->query('//table[@id="agentsReportTable"]/thead/tr/th');
        $this->assertCount(5, $headers);
        $this->assertSame(
            ['First Name', 'Last Name', 'Home Address', 'Email', 'Cellphone Number'],
            array_map(fn ($header) => trim($header->textContent), iterator_to_array($headers))
        );
    }

    public function test_excel_download_contains_the_same_rows_with_escaped_text_cells(): void
    {
        DB::table('users')->where('id', 10)->update([
            'fname' => '<script>alert(1)</script>',
            'first_address_line' => '=HYPERLINK("https://example.test")',
        ]);
        $response = $this->actingAs($this->user())->get('/agentsReport?export=excel');
        $response->assertOk()->assertDownload('Agents.xls');
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $content = $response->streamedContent();

        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $content);
        $this->assertStringNotContainsString('<script>', $content);
        $this->assertStringContainsString('x:str', $content);
        $this->assertStringContainsString('mso-number-format:', $content);
        $this->assertStringContainsString('0012345678', $content);
        $this->assertStringContainsString('=HYPERLINK(&quot;https://example.test&quot;)', $content);
        $this->assertStringNotContainsString('Commission %', $content);
        $this->assertStringNotContainsString('Zero Flag Agent', $content);
        $this->assertStringNotContainsString('Excluded Legacy Agent', $content);
        $this->assertSame(5, substr_count($content, '<th>'));
        $this->assertSame(5, substr_count($content, '<td '));
    }

    public function test_agents_and_guests_cannot_view_or_download_the_report(): void
    {
        $this->get('/agentsReport')->assertRedirect('/login');
        $this->get('/agentsReport?export=excel')->assertRedirect('/login');
        $this->actingAs($this->user(2));
        $this->get('/agentsReport')->assertForbidden();
        $this->get('/agentsReport?export=excel')->assertForbidden();
    }

    public function test_unsupported_export_formats_are_rejected(): void
    {
        $this->actingAs($this->user())->getJson('/agentsReport?export=unknown')
            ->assertUnprocessable()->assertJsonValidationErrors('export');
    }
}
