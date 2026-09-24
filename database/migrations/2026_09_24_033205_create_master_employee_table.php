<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_employee', function (Blueprint $table) {
            $table->string('e_code')->primary();
            $table->string('e_name');
            $table->string('e_d_code');
            $table->foreign('e_d_code')->references('d_code')->on('master_division')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_employee');
    }
};
