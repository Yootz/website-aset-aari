<?php

namespace Tests\Feature;

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

        $response = $this->postJson("/api/peminjaman/{$peminjamanCode}/return");

        $response->assertOk()
            ->assertJsonPath('data.p_status', 'returned')
            ->assertJsonPath('data.details.0.dt_status', 'returned');

        $this->assertDatabaseHas('master_aset', [
            'a_code' => 'ASSET-001',
            'a_status' => 'available',
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
}
