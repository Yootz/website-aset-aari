<?php

namespace Tests\Feature;

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

    public function test_guest_can_read_master_pages_and_create_loan_but_admin_pages_require_login(): void
    {
        $this->get('/employee')->assertOk();
        $this->get('/division')->assertOk();
        $this->get('/asset')->assertOk();
        $this->get('/peminjaman/create')->assertOk();

        $this->get('/')->assertRedirect(route('login'));
        $this->get('/employee/create')->assertRedirect(route('login'));
        $this->get('/division/create')->assertRedirect(route('login'));
        $this->get('/asset/create')->assertRedirect(route('login'));
        $this->get('/peminjaman')->assertRedirect(route('login'));
        $this->get('/monitoring-peminjaman')->assertRedirect(route('login'));
        $this->get('/laporan/peminjaman/bulanan')->assertRedirect(route('login'));
        $this->get('/createqr')->assertRedirect(route('login'));
        $this->getJson('/api/peminjaman')->assertUnauthorized();
        $this->getJson('/api/laporan/peminjaman/bulanan?month=9&year=2026')->assertUnauthorized();
    }

    public function test_non_admin_user_can_read_master_pages_but_cannot_use_admin_actions(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/employee')->assertOk()
            ->assertDontSee('Tambah karyawan')
            ->assertDontSee('Edit');
        $this->get('/division')->assertOk()
            ->assertDontSee('Tambah divisi')
            ->assertDontSee('Edit');
        $this->get('/asset')->assertOk()
            ->assertSee('Login admin')
            ->assertSee('Pinjam aset')
            ->assertDontSee('Tambah aset')
            ->assertDontSee('Buat QR aset');
        $this->get('/peminjaman/create')->assertOk();

        $this->get('/')->assertForbidden();
        $this->post('/asset', [])->assertForbidden();
        $this->get('/peminjaman')->assertForbidden();
        $this->getJson('/api/peminjaman')->assertForbidden();
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
}
