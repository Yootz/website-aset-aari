<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\DetailPeminjaman;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_accepts_only_an_admin_password(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertSee('type="password"', false)
            ->assertDontSee('type="email"', false);
    }

    public function test_configured_admin_password_logs_in_using_laravel_auth(): void
    {
        config([
            'admin.email' => 'admin@example.test',
            'admin.password' => 'correct-horse-battery',
        ]);

        $response = $this->post(route('login.store'), [
            'password' => 'correct-horse-battery',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.test',
            'is_admin' => true,
        ]);
    }

    public function test_invalid_admin_password_does_not_authenticate_or_create_a_user(): void
    {
        config(['admin.password' => 'correct-horse-battery']);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'password' => 'incorrect-password',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_guest_can_browse_loan_list_and_detail_while_admin_pages_require_login(): void
    {
        $loan = $this->createLoanForDisplay();

        $this->get('/employee')->assertOk();
        $this->get('/division')->assertOk();
        $this->get('/asset')->assertOk();
        $this->get('/peminjaman/create')->assertOk();
        $this->get('/peminjaman')->assertOk()
            ->assertSee('Peminjaman')
            ->assertSee('Login admin')
            ->assertDontSee('Monitoring');
        $this->get(route('peminjaman.show', $loan))->assertOk()
            ->assertSee($loan->p_code);

        $this->get('/')->assertRedirect(route('login'));
        $this->get('/employee/create')->assertRedirect(route('login'));
        $this->get('/division/create')->assertRedirect(route('login'));
        $this->get('/asset/create')->assertRedirect(route('login'));
        $this->get('/monitoring-peminjaman')->assertRedirect(route('login'));
        $this->get('/laporan/peminjaman/bulanan')->assertRedirect(route('login'));
        $this->get('/createqr')->assertRedirect(route('login'));
        $this->getJson('/api/peminjaman')->assertUnauthorized();
        $this->getJson('/api/laporan/peminjaman/bulanan?month=9&year=2026')->assertUnauthorized();
    }

    public function test_non_admin_user_can_read_master_pages_but_cannot_use_admin_actions(): void
    {
        $this->actingAs(User::factory()->create());

        $loan = $this->createLoanForDisplay();
        $asset = $loan->details()->firstOrFail()->asset;

        $this->get('/employee')->assertOk()
            ->assertDontSee('Tambah karyawan')
            ->assertDontSee('Edit');
        $this->get('/division')->assertOk()
            ->assertDontSee('Tambah divisi')
            ->assertDontSee('Edit');
        $this->get('/asset')->assertOk()
            ->assertSee('Login admin')
            ->assertSee('Peminjaman')
            ->assertSee('Pinjam aset')
            ->assertDontSee('Tambah aset')
            ->assertDontSee('Buat QR aset');
        $this->get('/peminjaman/create')->assertOk();
        $this->get('/peminjaman')->assertOk()
            ->assertSee(route('peminjaman.show', $loan), false)
            ->assertSee('Buat peminjaman')
            ->assertDontSee('Monitoring');
        $this->get(route('peminjaman.show', $loan))->assertOk()
            ->assertSee($loan->p_code)
            ->assertSee($asset->a_name);

        $this->get('/')->assertForbidden();
        $this->post('/asset', [])->assertForbidden();
        $this->get('/monitoring-peminjaman')->assertForbidden();
        $this->patch(route('peminjaman.status', $loan), ['p_status' => 'approved'])->assertForbidden();
        $this->getJson('/api/peminjaman')->assertForbidden();
    }

    private function createLoanForDisplay(): Peminjaman
    {
        $division = Division::create([
            'd_code' => 'DIV-TEST',
            'd_name' => 'Divisi Uji',
        ]);
        $employee = Employee::create([
            'e_code' => 'EMP-TEST',
            'e_name' => 'Karyawan Uji',
            'e_d_code' => $division->d_code,
        ]);
        $asset = Asset::create([
            'a_code' => 'AST-TEST',
            'a_name' => 'Laptop Uji',
            'a_type' => 'Elektronik',
            'a_desc' => 'Aset untuk pengujian.',
            'a_status' => 'unavailable',
        ]);
        $loan = Peminjaman::create([
            'p_code' => 'PJM-TEST',
            'e_code' => $employee->e_code,
            'tgl_pinjam' => '2026-09-29',
            'tgl_balik' => '2026-10-01',
            'p_status' => 'pending',
            'p_desc' => null,
        ]);

        DetailPeminjaman::create([
            'dt_code' => 'DT-TEST',
            'p_code' => $loan->p_code,
            'a_code' => $asset->a_code,
            'dt_qty' => 1,
            'dt_status' => 'borrowed',
        ]);

        return $loan;
    }

    public function test_admin_sees_the_full_navigation_and_can_log_out(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/asset')->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Peminjaman')
            ->assertSee('Monitoring')
            ->assertSee('Laporan')
            ->assertSee('QR aset')
            ->assertSee('Tambah aset')
            ->assertSee('Keluar');

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_dashboard_summary_shows_asset_and_loan_status_breakdowns(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->createLoanForDisplay();

        Asset::create([
            'a_code' => 'AST-AVAILABLE',
            'a_name' => 'Laptop tersedia',
            'a_type' => 'Elektronik',
            'a_desc' => 'Aset tersedia.',
            'a_status' => 'Available',
        ]);
        Asset::create([
            'a_code' => 'AST-MAINTENANCE',
            'a_name' => 'Laptop maintenance',
            'a_type' => 'Elektronik',
            'a_desc' => 'Aset maintenance.',
            'a_status' => 'maintenance',
        ]);

        Peminjaman::create([
            'p_code' => 'PJM-APPROVED',
            'e_code' => 'EMP-TEST',
            'tgl_pinjam' => '2026-10-01',
            'p_status' => 'approved',
        ]);
        Peminjaman::create([
            'p_code' => 'PJM-RETURNED',
            'e_code' => 'EMP-TEST',
            'tgl_pinjam' => '2026-09-20',
            'p_status' => 'returned',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Tersedia')
            ->assertSee('Dipinjam')
            ->assertSee('Maintenance')
            ->assertSee('Menunggu')
            ->assertSee('Berjalan')
            ->assertSee('Selesai')
            ->assertDontSee('Berisi anggota')
            ->assertDontSee('Rata-rata / divisi');
    }
}
