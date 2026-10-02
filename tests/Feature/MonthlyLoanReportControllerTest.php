<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MonthlyLoanReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_report_page_renders(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->get('/laporan/peminjaman/bulanan');

        $response->assertOk()
            ->assertSee('Laporan peminjaman bulanan.')
            ->assertSee('Tampilkan laporan')
            ->assertSee('Cetak laporan');
    }

    public function test_report_home_offers_monthly_and_current_asset_reports(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('laporan.index'))
            ->assertOk()
            ->assertSee('Laporan bulanan peminjaman')
            ->assertSee('Laporan aset saat ini')
            ->assertSee(route('laporan.peminjaman.bulanan'))
            ->assertSee(route('laporan.aset'));
    }

    public function test_monthly_report_returns_loan_details_for_the_requested_month(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->createEmployeeAndAsset();
        $this->createLoan('PJM-SEP', '2026-09-01', '2026-09-04', 'approved', 'borrowed');
        $this->createLoan('PJM-OCT', '2026-10-01', '2026-10-02', 'returned', 'returned');

        $response = $this->getJson('/api/laporan/peminjaman/bulanan?month=9&year=2026');

        $response->assertOk()
            ->assertJsonPath('period.month', 9)
            ->assertJsonPath('period.year', 2026)
            ->assertJsonPath('summary.total_transaksi', 1)
            ->assertJsonPath('summary.total_aset', 1)
            ->assertJsonPath('data.0.p_code', 'PJM-SEP')
            ->assertJsonPath('data.0.peminjam.nama', 'Employee')
            ->assertJsonPath('data.0.aset.nama', 'Laptop')
            ->assertJsonPath('data.0.durasi_hari', 3)
            ->assertJsonPath('data.0.status_peminjaman', 'approved')
            ->assertJsonPath('data.0.status_aset', 'borrowed');
    }

    public function test_monthly_report_rejects_an_invalid_month(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->getJson('/api/laporan/peminjaman/bulanan?month=13&year=2026');

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['month']);
    }

    private function createEmployeeAndAsset(): void
    {
        DB::table('master_division')->insert([
            'd_code' => 'DIV-001',
            'd_name' => 'Division',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('master_employee')->insert([
            'e_code' => 'EMP-001',
            'e_name' => 'Employee',
            'e_d_code' => 'DIV-001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('master_aset')->insert([
            'a_code' => 'AST-001',
            'a_name' => 'Laptop',
            'a_type' => 'Equipment',
            'a_desc' => 'Test asset',
            'a_status' => 'unavailable',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createLoan(
        string $loanCode,
        string $loanDate,
        string $returnDate,
        string $loanStatus,
        string $detailStatus,
    ): void {
        DB::table('peminjaman')->insert([
            'p_code' => $loanCode,
            'tgl_pinjam' => $loanDate,
            'tgl_balik' => $returnDate,
            'e_code' => 'EMP-001',
            'p_status' => $loanStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_peminjaman')->insert([
            'dt_code' => 'DT-'.$loanCode,
            'p_code' => $loanCode,
            'a_code' => 'AST-001',
            'dt_qty' => 1,
            'dt_status' => $detailStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
