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
        Schema::create('loan_details', function (Blueprint $table) {
            $table->id();
            $table->string('ld_l_code', 20);
            $table->string('ld_a_code', 20);
            $table->unsignedInteger('ld_qty');
            $table->string('ld_status', 20)->default('borrowed');
            $table->timestamps();

            $table->foreign('ld_l_code')
                ->references('l_code')
                ->on('loans')
                ->cascadeOnDelete();
            $table->foreign('ld_a_code')
                ->references('a_code')
                ->on('master_aset')
                ->restrictOnDelete();
            $table->index(['ld_l_code', 'ld_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_details');
    }
};
