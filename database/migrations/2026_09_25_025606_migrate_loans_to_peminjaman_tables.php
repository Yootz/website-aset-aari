<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('loans') || ! Schema::hasTable('loan_details')) {
            return;
        }

        DB::table('loans')->orderBy('l_code')->each(function (object $loan): void {
            DB::table('peminjaman')->insertOrIgnore([
                'p_code' => $loan->l_code,
                'tgl_pinjam' => $loan->l_date,
                'tgl_balik' => $loan->l_return_date_plan,
                'e_code' => $loan->l_e_code,
                'p_desc' => $loan->l_note,
                'p_status' => $loan->l_status,
                'created_at' => $loan->created_at,
                'updated_at' => $loan->updated_at,
            ]);
        });

        DB::table('loan_details')->orderBy('id')->each(function (object $detail): void {
            DB::table('detail_peminjaman')->insertOrIgnore([
                'dt_code' => 'DT-'.$detail->id,
                'p_code' => $detail->ld_l_code,
                'a_code' => $detail->ld_a_code,
                'dt_qty' => $detail->ld_qty,
                'dt_status' => $detail->ld_status,
                'created_at' => $detail->created_at,
                'updated_at' => $detail->updated_at,
            ]);
        });

        Schema::dropIfExists('loan_details');
        Schema::dropIfExists('loans');
    }

    public function down(): void
    {
        throw new RuntimeException('The loan table transition cannot be reversed safely.');
    }
};
