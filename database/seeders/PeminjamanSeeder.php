<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $peminjaman = [
            ['p_code' => 'PJM-001', 'e_code' => 'EMP001', 'tgl_pinjam' => '2024-01-15', 'tgl_balik' => '2024-01-20', 'p_status' => 'returned', 'p_desc' => 'Pinjam laptop untuk presentasi klien'],
            ['p_code' => 'PJM-002', 'e_code' => 'EMP002', 'tgl_pinjam' => '2024-01-18', 'tgl_balik' => '2024-01-25', 'p_status' => 'returned', 'p_desc' => 'Pinjam proyektor untuk rapat bulanan'],
            ['p_code' => 'PJM-003', 'e_code' => 'EMP003', 'tgl_pinjam' => '2024-02-01', 'tgl_balik' => '2024-02-05', 'p_status' => 'returned', 'p_desc' => 'Pinjam kamera untuk dokumentasi event'],
            ['p_code' => 'PJM-004', 'e_code' => 'EMP004', 'tgl_pinjam' => '2024-02-10', 'tgl_balik' => '2024-02-15', 'p_status' => 'returned', 'p_desc' => 'Pinjam printer untuk cetak laporan keuangan'],
            ['p_code' => 'PJM-005', 'e_code' => 'EMP005', 'tgl_pinjam' => '2024-02-20', 'tgl_balik' => '2024-02-28', 'p_status' => 'returned', 'p_desc' => 'Pinjam monitor untuk workstation baru'],
            ['p_code' => 'PJM-006', 'e_code' => 'EMP006', 'tgl_pinjam' => '2024-03-01', 'tgl_balik' => '2024-03-07', 'p_status' => 'returned', 'p_desc' => 'Pinjam tablet untuk survey lapangan'],
            ['p_code' => 'PJM-007', 'e_code' => 'EMP007', 'tgl_pinjam' => '2024-03-10', 'tgl_balik' => '2024-03-15', 'p_status' => 'returned', 'p_desc' => 'Pinjam speaker untuk acara internal'],
            ['p_code' => 'PJM-008', 'e_code' => 'EMP008', 'tgl_pinjam' => '2024-03-20', 'tgl_balik' => '2024-03-25', 'p_status' => 'returned', 'p_desc' => 'Pinjam laptop untuk training karyawan baru'],
            ['p_code' => 'PJM-009', 'e_code' => 'EMP009', 'tgl_pinjam' => '2024-04-01', 'tgl_balik' => '2024-04-05', 'p_status' => 'returned', 'p_desc' => 'Pinjam proyektor untuk presentasi produk'],
            ['p_code' => 'PJM-010', 'e_code' => 'EMP010', 'tgl_pinjam' => '2024-04-10', 'tgl_balik' => '2024-04-15', 'p_status' => 'returned', 'p_desc' => 'Pinjam kamera untuk foto profil karyawan'],
            ['p_code' => 'PJM-011', 'e_code' => 'EMP011', 'tgl_pinjam' => '2024-04-20', 'tgl_balik' => '2024-04-25', 'p_status' => 'returned', 'p_desc' => 'Pinjam printer untuk cetak kontrak'],
            ['p_code' => 'PJM-012', 'e_code' => 'EMP012', 'tgl_pinjam' => '2024-05-01', 'tgl_balik' => '2024-05-10', 'p_status' => 'returned', 'p_desc' => 'Pinjam monitor untuk setup workstation'],
            ['p_code' => 'PJM-013', 'e_code' => 'EMP013', 'tgl_pinjam' => '2024-05-15', 'tgl_balik' => '2024-05-20', 'p_status' => 'returned', 'p_desc' => 'Pinjam tablet untuk demo aplikasi'],
            ['p_code' => 'PJM-014', 'e_code' => 'EMP014', 'tgl_pinjam' => '2024-05-25', 'tgl_balik' => '2024-05-30', 'p_status' => 'returned', 'p_desc' => 'Pinjam speaker untuk townhall meeting'],
            ['p_code' => 'PJM-015', 'e_code' => 'EMP015', 'tgl_pinjam' => '2024-06-01', 'tgl_balik' => '2024-06-07', 'p_status' => 'returned', 'p_desc' => 'Pinjam laptop untuk work from home'],
            ['p_code' => 'PJM-016', 'e_code' => 'EMP016', 'tgl_pinjam' => '2024-06-10', 'tgl_balik' => '2024-06-15', 'p_status' => 'returned', 'p_desc' => 'Pinjam proyektor untuk workshop'],
            ['p_code' => 'PJM-017', 'e_code' => 'EMP017', 'tgl_pinjam' => '2024-06-20', 'tgl_balik' => '2024-06-25', 'p_status' => 'returned', 'p_desc' => 'Pinjam kamera untuk video company profile'],
            ['p_code' => 'PJM-018', 'e_code' => 'EMP018', 'tgl_pinjam' => '2024-07-01', 'tgl_balik' => '2024-07-05', 'p_status' => 'returned', 'p_desc' => 'Pinjam printer untuk cetak sertifikat'],
            ['p_code' => 'PJM-019', 'e_code' => 'EMP019', 'tgl_pinjam' => '2024-07-10', 'tgl_balik' => '2024-07-15', 'p_status' => 'returned', 'p_desc' => 'Pinjam monitor untuk design review'],
            ['p_code' => 'PJM-020', 'e_code' => 'EMP020', 'tgl_pinjam' => '2024-07-20', 'tgl_balik' => '2024-07-25', 'p_status' => 'returned', 'p_desc' => 'Pinjam tablet untuk audit lapangan'],
            ['p_code' => 'PJM-021', 'e_code' => 'EMP021', 'tgl_pinjam' => '2024-08-01', 'tgl_balik' => '2024-08-07', 'p_status' => 'borrowed', 'p_desc' => 'Pinjam speaker untuk event marketing'],
            ['p_code' => 'PJM-022', 'e_code' => 'EMP022', 'tgl_pinjam' => '2024-08-10', 'tgl_balik' => '2024-08-15', 'p_status' => 'borrowed', 'p_desc' => 'Pinjam laptop untuk onboarding'],
            ['p_code' => 'PJM-023', 'e_code' => 'EMP023', 'tgl_pinjam' => '2024-08-20', 'tgl_balik' => '2024-08-25', 'p_status' => 'borrowed', 'p_desc' => 'Pinjam proyektor untuk training'],
            ['p_code' => 'PJM-024', 'e_code' => 'EMP024', 'tgl_pinjam' => '2024-09-01', 'tgl_balik' => '2024-09-05', 'p_status' => 'borrowed', 'p_desc' => 'Pinjam kamera untuk dokumentasi proyek'],
            ['p_code' => 'PJM-025', 'e_code' => 'EMP025', 'tgl_pinjam' => '2024-09-10', 'tgl_balik' => '2024-09-15', 'p_status' => 'borrowed', 'p_desc' => 'Pinjam printer untuk cetak proposal'],
            ['p_code' => 'PJM-026', 'e_code' => 'EMP026', 'tgl_pinjam' => '2024-09-20', 'tgl_balik' => '2024-09-25', 'p_status' => 'borrowed', 'p_desc' => 'Pinjam monitor untuk extended display'],
            ['p_code' => 'PJM-027', 'e_code' => 'EMP027', 'tgl_pinjam' => '2024-10-01', 'tgl_balik' => '2024-10-07', 'p_status' => 'pending', 'p_desc' => 'Pinjam tablet untuk presentasi klien'],
            ['p_code' => 'PJM-028', 'e_code' => 'EMP028', 'tgl_pinjam' => '2024-10-10', 'tgl_balik' => '2024-10-15', 'p_status' => 'pending', 'p_desc' => 'Pinjam speaker untuk seminar'],
            ['p_code' => 'PJM-029', 'e_code' => 'EMP029', 'tgl_pinjam' => '2024-10-20', 'tgl_balik' => '2024-10-25', 'p_status' => 'pending', 'p_desc' => 'Pinjam laptop untuk development'],
            ['p_code' => 'PJM-030', 'e_code' => 'EMP030', 'tgl_pinjam' => '2024-11-01', 'tgl_balik' => '2024-11-05', 'p_status' => 'pending', 'p_desc' => 'Pinjam proyektor untuk quarterly review'],
        ];

        foreach ($peminjaman as $pinjam) {
            Peminjaman::create($pinjam);
        }
    }
}