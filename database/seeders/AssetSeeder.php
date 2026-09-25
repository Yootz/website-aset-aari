<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = now();

        DB::table('master_aset')->upsert([
            [
                'a_code' => 'AST-001',
                'a_name' => 'Laptop Lenovo ThinkPad',
                'a_type' => 'Laptop',
                'a_desc' => 'Laptop kerja untuk kebutuhan operasional dan administrasi.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-002',
                'a_name' => 'Laptop ASUS VivoBook',
                'a_type' => 'Laptop',
                'a_desc' => 'Laptop kerja untuk kegiatan lapangan dan presentasi.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-003',
                'a_name' => 'Monitor Dell 24 Inch',
                'a_type' => 'Monitor',
                'a_desc' => 'Monitor eksternal untuk workstation kantor.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-004',
                'a_name' => 'Proyektor Epson',
                'a_type' => 'Proyektor',
                'a_desc' => 'Proyektor untuk rapat dan kebutuhan presentasi.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-005',
                'a_name' => 'Kamera Canon EOS',
                'a_type' => 'Kamera',
                'a_desc' => 'Kamera dokumentasi kegiatan internal dan eksternal.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-006',
                'a_name' => 'Tripod Kamera',
                'a_type' => 'Aksesori Kamera',
                'a_desc' => 'Tripod pendukung dokumentasi dan produksi konten.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-007',
                'a_name' => 'Printer Multifungsi',
                'a_type' => 'Printer',
                'a_desc' => 'Printer kantor dengan fungsi cetak, pindai, dan salin.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-008',
                'a_name' => 'Router Wi-Fi',
                'a_type' => 'Perangkat Jaringan',
                'a_desc' => 'Perangkat jaringan untuk konektivitas ruang kerja.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-009',
                'a_name' => 'Tablet Samsung Galaxy',
                'a_type' => 'Tablet',
                'a_desc' => 'Tablet untuk pendataan dan pemeriksaan aset di lapangan.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'a_code' => 'AST-010',
                'a_name' => 'Speaker Portable',
                'a_type' => 'Audio',
                'a_desc' => 'Speaker portabel untuk rapat dan kegiatan internal.',
                'a_status' => 'Available',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ], ['a_code'], ['a_name', 'a_type', 'a_desc', 'a_status', 'updated_at']);
    }
}
