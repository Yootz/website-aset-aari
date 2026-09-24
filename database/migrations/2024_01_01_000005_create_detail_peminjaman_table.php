<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->string('dt_code', 20)->primary();
            $table->string('p_code', 20);
            $table->string('a_code', 20);
            $table->foreign('p_code')->references('p_code')->on('peminjaman')->onDelete('cascade');
            $table->foreign('a_code')->references('a_code')->on('master_aset')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
