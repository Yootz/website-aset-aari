<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPeminjaman;

class DetailPeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            ['dt_code' => 'DTL-001', 'p_code' => 'PJM-001', 'a_code' => 'AST-001', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-002', 'p_code' => 'PJM-001', 'a_code' => 'AST-003', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-003', 'p_code' => 'PJM-002', 'a_code' => 'AST-004', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-004', 'p_code' => 'PJM-003', 'a_code' => 'AST-005', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-005', 'p_code' => 'PJM-003', 'a_code' => 'AST-006', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-006', 'p_code' => 'PJM-004', 'a_code' => 'AST-007', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-007', 'p_code' => 'PJM-005', 'a_code' => 'AST-003', 'dt_qty' => 2, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-008', 'p_code' => 'PJM-006', 'a_code' => 'AST-009', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-009', 'p_code' => 'PJM-007', 'a_code' => 'AST-010', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-010', 'p_code' => 'PJM-008', 'a_code' => 'AST-002', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-011', 'p_code' => 'PJM-009', 'a_code' => 'AST-013', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-012', 'p_code' => 'PJM-010', 'a_code' => 'AST-014', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-013', 'p_code' => 'PJM-010', 'a_code' => 'AST-015', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-014', 'p_code' => 'PJM-011', 'a_code' => 'AST-016', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-015', 'p_code' => 'PJM-012', 'a_code' => 'AST-012', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-016', 'p_code' => 'PJM-013', 'a_code' => 'AST-018', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-017', 'p_code' => 'PJM-014', 'a_code' => 'AST-019', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-018', 'p_code' => 'PJM-015', 'a_code' => 'AST-011', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-019', 'p_code' => 'PJM-016', 'a_code' => 'AST-022', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-020', 'p_code' => 'PJM-017', 'a_code' => 'AST-023', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-021', 'p_code' => 'PJM-017', 'a_code' => 'AST-024', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-022', 'p_code' => 'PJM-018', 'a_code' => 'AST-025', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-023', 'p_code' => 'PJM-019', 'a_code' => 'AST-021', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-024', 'p_code' => 'PJM-020', 'a_code' => 'AST-027', 'dt_qty' => 1, 'dt_status' => 'returned'],
            ['dt_code' => 'DTL-025', 'p_code' => 'PJM-021', 'a_code' => 'AST-019', 'dt_qty' => 1, 'dt_status' => 'borrowed'],
            ['dt_code' => 'DTL-026', 'p_code' => 'PJM-022', 'a_code' => 'AST-020', 'dt_qty' => 1, 'dt_status' => 'borrowed'],
            ['dt_code' => 'DTL-027', 'p_code' => 'PJM-023', 'a_code' => 'AST-022', 'dt_qty' => 1, 'dt_status' => 'borrowed'],
            ['dt_code' => 'DTL-028', 'p_code' => 'PJM-024', 'a_code' => 'AST-023', 'dt_qty' => 1, 'dt_status' => 'borrowed'],
            ['dt_code' => 'DTL-029', 'p_code' => 'PJM-025', 'a_code' => 'AST-025', 'dt_qty' => 1, 'dt_status' => 'borrowed'],
            ['dt_code' => 'DTL-030', 'p_code' => 'PJM-026', 'a_code' => 'AST-030', 'dt_qty' => 1, 'dt_status' => 'borrowed'],
        ];

        foreach ($details as $detail) {
            DetailPeminjaman::create($detail);
        }
    }
}