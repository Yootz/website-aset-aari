<?php

namespace Tests\Feature;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PeminjamanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_payload_creates_loan_and_marks_asset_unavailable(): void
    {
        $this->createEmployeeAndAsset('EMP-001', 'ASSET-001', 'available');

        $response = $this->postJson('/api/peminjaman', [
            'e_code' => 'EMP-001',
            'tgl_pinjam' => '2026-09-25',
            'tgl_balik' => '2026-10-01',
            'details' => [
                ['a_code' => 'ASSET-001', 'dt_qty' => 1],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.p_status', 'pending')
            ->assertJsonPath('data.details.0.dt_status', 'borrowed');

        $this->assertDatabaseHas('peminjaman', ['e_code' => 'EMP-001']);
        $this->assertDatabaseHas('detail_peminjaman', ['a_code' => 'ASSET-001']);
        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'ASSET-001',
            'a_status' => 'unavailable',
        ]);
    }

    public function test_unavailable_asset_rejects_loan_without_persisting_transaction(): void
    {
        $this->createEmployeeAndAsset('EMP-001', 'ASSET-001', 'unavailable');

        $response = $this->postJson('/api/peminjaman', [
            'e_code' => 'EMP-001',
            'tgl_pinjam' => '2026-09-25',
            'tgl_balik' => '2026-10-01',
            'details' => [
                ['a_code' => 'ASSET-001', 'dt_qty' => 1],
            ],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['details'])
            ->assertJsonPath('errors.details.0', 'One or more assets are not available: ASSET-001.');

        $this->assertDatabaseCount('peminjaman', 0);
        $this->assertDatabaseCount('detail_peminjaman', 0);
    }

    public function test_quantity_greater_than_one_is_rejected(): void
    {
        $this->createEmployeeAndAsset('EMP-001', 'ASSET-001', 'available');

        $response = $this->postJson('/api/peminjaman', [
            'e_code' => 'EMP-001',
            'tgl_pinjam' => '2026-09-25',
            'tgl_balik' => '2026-10-01',
            'details' => [
                ['a_code' => 'ASSET-001', 'dt_qty' => 2],
            ],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['details.0.dt_qty']);

        $this->assertDatabaseCount('peminjaman', 0);
        $this->assertDatabaseCount('detail_peminjaman', 0);
    }

    public function test_return_endpoint_marks_peminjaman_details_and_assets_available(): void
    {
        $this->createEmployeeAndAsset('EMP-001', 'ASSET-001', 'available');

        $createResponse = $this->postJson('/api/peminjaman', [
            'e_code' => 'EMP-001',
            'tgl_pinjam' => '2026-09-25',
            'tgl_balik' => '2026-10-01',
            'details' => [
                ['a_code' => 'ASSET-001', 'dt_qty' => 1],
            ],
        ]);

        $peminjamanCode = $createResponse->json('data.p_code');
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->postJson("/api/peminjaman/{$peminjamanCode}/return");

        $response->assertOk()
            ->assertJsonPath('data.p_status', 'returned')
            ->assertJsonPath('data.details.0.dt_status', 'returned');

        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'ASSET-001',
            'a_status' => 'available',
        ]);
    }

    public function test_approving_loan_marks_all_related_assets_unavailable(): void
    {
        $this->createEmployeeAndAsset('EMP-001', 'ASSET-001', 'available');
        DB::table('master_aset')->insert([
            'a_code' => 'ASSET-002',
            'a_name' => 'Second asset',
            'a_type' => 'Equipment',
            'a_desc' => 'Another test asset',
            'a_status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $createResponse = $this->postJson('/api/peminjaman', [
            'e_code' => 'EMP-001',
            'tgl_pinjam' => '2026-09-25',
            'tgl_balik' => '2026-10-01',
            'details' => [
                ['a_code' => 'ASSET-001', 'dt_qty' => 1],
                ['a_code' => 'ASSET-002', 'dt_qty' => 1],
            ],
        ]);

        $createResponse->assertCreated();
        $peminjamanCode = $createResponse->json('data.p_code');
        DB::table('master_aset')
            ->whereIn('a_code', ['ASSET-001', 'ASSET-002'])
            ->update(['a_status' => 'pending']);
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->patchJson("/api/peminjaman/{$peminjamanCode}/status", [
            'p_status' => 'approved',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.p_status', 'approved');
        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'ASSET-001',
            'a_status' => 'unavailable',
        ]);
        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'ASSET-002',
            'a_status' => 'unavailable',
        ]);
    }

    public function test_edit_form_shows_database_dates_and_leaves_null_return_date_blank(): void
    {
        $peminjamanCode = $this->createPeminjamanForEdit(null);

        $this->get(route('peminjaman.edit', $peminjamanCode))
            ->assertOk()
            ->assertSee('name="tgl_pinjam" value="2026-09-25"', false)
            ->assertSee('name="tgl_balik" value=""', false);
    }

    public function test_edit_updates_dates_and_accepts_a_blank_return_date(): void
    {
        $peminjamanCode = $this->createPeminjamanForEdit('2026-10-01');

        $this->put(route('peminjaman.update', $peminjamanCode), [
            'e_code' => 'EMP-EDIT',
            'tgl_pinjam' => '2026-09-26',
            'tgl_balik' => '',
            'details' => [
                ['a_code' => 'ASSET-EDIT', 'dt_qty' => 1],
            ],
        ])->assertRedirect(route('peminjaman.index'));

        $peminjaman = Peminjaman::findOrFail($peminjamanCode);
        $storedLoanDate = DB::table('peminjaman')->where('p_code', $peminjamanCode)->value('tgl_pinjam');

        $this->assertSame('2026-09-26', substr((string) $storedLoanDate, 0, 10));
        $this->assertNull($peminjaman->tgl_balik);
    }

    public function test_edit_can_remove_an_asset_and_add_an_available_asset(): void
    {
        $peminjamanCode = $this->createPeminjamanForEdit(null);
        DB::table('master_aset')->insert([
            'a_code' => 'ASSET-NEW',
            'a_name' => 'New asset',
            'a_type' => 'Equipment',
            'a_desc' => 'Available asset for edit test',
            'a_status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->put(route('peminjaman.update', $peminjamanCode), [
            'e_code' => 'EMP-EDIT',
            'tgl_pinjam' => '2026-09-25',
            'tgl_balik' => '',
            'details' => [
                ['a_code' => 'ASSET-NEW', 'dt_qty' => 1],
            ],
        ])->assertRedirect(route('peminjaman.index'));

        $this->assertDatabaseMissing('detail_peminjaman', [
            'p_code' => $peminjamanCode,
            'a_code' => 'ASSET-EDIT',
        ]);
        $this->assertDatabaseHas('detail_peminjaman', [
            'p_code' => $peminjamanCode,
            'a_code' => 'ASSET-NEW',
        ]);
        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'ASSET-EDIT',
            'a_status' => 'available',
        ]);
        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'ASSET-NEW',
            'a_status' => 'pending',
        ]);
    }

    private function createEmployeeAndAsset(string $employeeCode, string $assetCode, string $assetStatus): void
    {
        DB::table('master_division')->insert([
            'd_code' => 'DIV-001',
            'd_name' => 'Division',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('master_employee')->insert([
            'e_code' => $employeeCode,
            'e_name' => 'Employee',
            'e_d_code' => 'DIV-001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('master_aset')->insert([
            'a_code' => $assetCode,
            'a_name' => 'Asset',
            'a_type' => 'Equipment',
            'a_desc' => 'Test asset',
            'a_status' => $assetStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createPeminjamanForEdit(?string $tglBalik): string
    {
        $this->createEmployeeAndAsset('EMP-EDIT', 'ASSET-EDIT', 'pending');
        DB::table('peminjaman')->insert([
            'p_code' => 'PJM-EDIT',
            'tgl_pinjam' => '2026-09-25',
            'tgl_balik' => $tglBalik,
            'e_code' => 'EMP-EDIT',
            'p_desc' => 'Existing description',
            'p_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('detail_peminjaman')->insert([
            'dt_code' => 'DT-EDIT',
            'p_code' => 'PJM-EDIT',
            'a_code' => 'ASSET-EDIT',
            'dt_qty' => 1,
            'dt_status' => 'borrowed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return 'PJM-EDIT';
    }
}
