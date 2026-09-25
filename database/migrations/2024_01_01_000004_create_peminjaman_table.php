<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->string('p_code', 20)->primary();
            $table->date('tgl_pinjam');
            $table->date('tgl_balik')->nullable();
            $table->string('e_code', 20);
            $table->text('p_desc')->nullable();
            $table->string('p_status', 20)->default('pending');
            $table->foreign('e_code')->references('e_code')->on('master_employee')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
