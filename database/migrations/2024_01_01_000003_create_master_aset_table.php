<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_aset', function (Blueprint $table) {
            $table->string('a_code', 20)->primary();
            $table->string('a_name', 100);
            $table->enum('a_type', ['asset', 'small asset']);
            $table->text('a_desc')->nullable();
            $table->enum('a_status', ['available', 'not'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_aset');
    }
};
