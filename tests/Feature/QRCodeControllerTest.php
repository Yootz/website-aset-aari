<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QRCodeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_asset_page_provides_a_png_download_button(): void
    {
        DB::table('master_aset')->insert([
            'a_code' => 'AST-001',
            'a_name' => 'Laptop kerja',
            'a_type' => 'Laptop',
            'a_desc' => 'Laptop untuk tim operasional.',
            'a_status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('createqr.index', ['a_code' => 'AST-001']));

        $response->assertOk()
            ->assertSee('id="downloadQrPng"', false)
            ->assertSee('data-asset-code="AST-001"', false)
            ->assertSee('<canvas id="qrCanvas"', false)
            ->assertDontSee('window.print()', false);
    }
}
