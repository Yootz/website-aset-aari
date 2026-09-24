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
        Schema::create('master_aset', function (Blueprint $table) {
            $table->string('a_code')->primary();
            $table->string('a_name');
            $table->string('a_type');
            $table->string('a_desc');
            $table->string('a_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_aset');
    }
};
