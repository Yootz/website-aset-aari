<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_renders_the_asset_form(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->get('/asset/create');

        $response->assertOk()
            ->assertSee('Daftarkan aset baru.')
            ->assertSee('a_code')
            ->assertSee('a_name')
            ->assertSee('a_type')
            ->assertSee('a_desc')
            ->assertSee('a_status');
    }

    public function test_valid_asset_form_creates_an_available_asset(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->post('/asset', [
            'a_code' => 'AST-101',
            'a_name' => 'Laptop kerja',
            'a_type' => 'Laptop',
            'a_desc' => 'Laptop untuk tim operasional.',
            'a_status' => 'available',
        ]);

        $response->assertRedirect(route('asset.index'))
            ->assertSessionHas('success', 'Aset berhasil ditambahkan.');

        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'AST-101',
            'a_name' => 'Laptop kerja',
            'a_type' => 'Laptop',
            'a_desc' => 'Laptop untuk tim operasional.',
            'a_status' => 'available',
        ]);
    }

    public function test_asset_form_rejects_a_duplicate_code(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->post('/asset', [
            'a_code' => 'AST-101',
            'a_name' => 'Laptop kerja',
            'a_type' => 'Laptop',
            'a_desc' => 'Laptop untuk tim operasional.',
            'a_status' => 'available',
        ]);

        $response = $this->from('/asset/create')->post('/asset', [
            'a_code' => 'AST-101',
            'a_name' => 'Laptop cadangan',
            'a_type' => 'Laptop',
            'a_desc' => 'Laptop duplikat.',
            'a_status' => 'available',
        ]);

        $response->assertRedirect('/asset/create')
            ->assertSessionHasErrors(['a_code']);

        $this->assertDatabaseCount('master_aset', 1);
    }

    public function test_asset_index_can_be_filtered_by_status(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->createAsset('AST-AVAILABLE', 'available');
        $this->createAsset('AST-MAINTENANCE', 'Maintenance');

        $this->get('/asset?a_status=maintenance')
            ->assertOk()
            ->assertSee('AST-MAINTENANCE')
            ->assertDontSee('AST-AVAILABLE');
    }

    public function test_admin_can_toggle_an_asset_between_available_and_maintenance(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->createAsset('AST-101', 'available');

        $this->from('/asset')->post('/asset/AST-101/maintenance')
            ->assertRedirect('/asset')
            ->assertSessionHas('success', 'Aset berhasil diatur ke status maintenance.');

        $this->assertDatabaseHas('master_aset', ['a_code' => 'AST-101', 'a_status' => 'maintenance']);

        $this->from('/asset')->post('/asset/AST-101/maintenance')
            ->assertRedirect('/asset')
            ->assertSessionHas('success', 'Aset berhasil diatur kembali menjadi available.');

        $this->assertDatabaseHas('master_aset', ['a_code' => 'AST-101', 'a_status' => 'available']);
    }

    public function test_asset_report_lists_code_name_and_current_status(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->createAsset('AST-REPORT', 'maintenance', 'Laptop kantor');

        $this->get(route('laporan.aset'))
            ->assertOk()
            ->assertSee('Kode aset')
            ->assertSee('Nama aset')
            ->assertSee('Status aset')
            ->assertSee('AST-REPORT')
            ->assertSee('Laptop kantor')
            ->assertSee('Maintenance')
            ->assertSee('Cetak laporan');
    }

    public function test_asset_report_filters_by_status_and_summarizes_the_applied_filter(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->createAsset('AST-AVAILABLE', 'available');
        $this->createAsset('AST-MAINTENANCE', 'maintenance');

        $this->get(route('laporan.aset', ['a_status' => 'maintenance']))
            ->assertOk()
            ->assertSee('AST-MAINTENANCE')
            ->assertDontSee('AST-AVAILABLE')
            ->assertSee('Filter diterapkan:')
            ->assertSee('Maintenance');
    }

    private function createAsset(string $assetCode, string $assetStatus, string $assetName = 'Test asset'): void
    {
        DB::table('master_aset')->insert([
            'a_code' => $assetCode,
            'a_name' => $assetName,
            'a_type' => 'Equipment',
            'a_desc' => 'Test asset description',
            'a_status' => $assetStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
