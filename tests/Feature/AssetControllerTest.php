<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
