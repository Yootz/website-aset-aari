<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['d_code' => 'DIV001', 'd_name' => 'IT Support', 'd_desc' => 'Divisi Teknologi Informasi'],
            ['d_code' => 'DIV002', 'd_name' => 'HRD', 'd_desc' => 'Divisi Sumber Daya Manusia'],
            ['d_code' => 'DIV003', 'd_name' => 'Finance', 'd_desc' => 'Divisi Keuangan'],
            ['d_code' => 'DIV004', 'd_name' => 'Marketing', 'd_desc' => 'Divisi Pemasaran dan Promosi'],
            ['d_code' => 'DIV005', 'd_name' => 'Operations', 'd_desc' => 'Divisi Operasional'],
            ['d_code' => 'DIV006', 'd_name' => 'Legal', 'd_desc' => 'Divisi Hukum dan Kepatuhan'],
            ['d_code' => 'DIV007', 'd_name' => 'R&D', 'd_desc' => 'Divisi Riset dan Pengembangan'],
            ['d_code' => 'DIV008', 'd_name' => 'Procurement', 'd_desc' => 'Divisi Pengadaan'],
            ['d_code' => 'DIV009', 'd_name' => 'Customer Service', 'd_desc' => 'Divisi Layanan Pelanggan'],
            ['d_code' => 'DIV010', 'd_name' => 'Quality Assurance', 'd_desc' => 'Divisi Jaminan Kualitas'],
            ['d_code' => 'DIV011', 'd_name' => 'Business Development', 'd_desc' => 'Divisi Pengembangan Bisnis'],
            ['d_code' => 'DIV012', 'd_name' => 'Internal Audit', 'd_desc' => 'Divisi Audit Internal'],
            ['d_code' => 'DIV013', 'd_name' => 'Corporate Secretary', 'd_desc' => 'Divisi Sekretaris Perusahaan'],
            ['d_code' => 'DIV014', 'd_name' => 'Facility Management', 'd_desc' => 'Divisi Manajemen Fasilitas'],
            ['d_code' => 'DIV015', 'd_name' => 'Security', 'd_desc' => 'Divisi Keamanan'],
            ['d_code' => 'DIV016', 'd_name' => 'Logistics', 'd_desc' => 'Divisi Logistik'],
            ['d_code' => 'DIV017', 'd_name' => 'Training & Development', 'd_desc' => 'Divisi Pelatihan dan Pengembangan'],
            ['d_code' => 'DIV018', 'd_name' => 'Public Relations', 'd_desc' => 'Divisi Hubungan Masyarakat'],
            ['d_code' => 'DIV019', 'd_name' => 'Data Analytics', 'd_desc' => 'Divisi Analisis Data'],
            ['d_code' => 'DIV020', 'd_name' => 'Project Management', 'd_desc' => 'Divisi Manajemen Proyek'],
            ['d_code' => 'DIV021', 'd_name' => 'Sales', 'd_desc' => 'Divisi Penjualan'],
            ['d_code' => 'DIV022', 'd_name' => 'Accounting', 'd_desc' => 'Divisi Akuntansi'],
            ['d_code' => 'DIV023', 'd_name' => 'Administration', 'd_desc' => 'Divisi Administrasi'],
            ['d_code' => 'DIV024', 'd_name' => 'Engineering', 'd_desc' => 'Divisi Teknik'],
            ['d_code' => 'DIV025', 'd_name' => 'Creative', 'd_desc' => 'Divisi Kreatif'],
            ['d_code' => 'DIV026', 'd_name' => 'Product Management', 'd_desc' => 'Divisi Manajemen Produk'],
            ['d_code' => 'DIV027', 'd_name' => 'Supply Chain', 'd_desc' => 'Divisi Rantai Pasokan'],
            ['d_code' => 'DIV028', 'd_name' => 'Risk Management', 'd_desc' => 'Divisi Manajemen Risiko'],
            ['d_code' => 'DIV029', 'd_name' => 'Compliance', 'd_desc' => 'Divisi Kepatuhan'],
            ['d_code' => 'DIV030', 'd_name' => 'Strategy', 'd_desc' => 'Divisi Strategi Korporat'],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}   