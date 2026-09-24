<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_employee', function (Blueprint $table) {
            $table->string('e_code', 20)->primary();
            $table->string('e_name', 100);
            $table->string('e_d_code', 20);
            $table->foreign('e_d_code')->references('d_code')->on('master_division')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_employee');
    }
};
