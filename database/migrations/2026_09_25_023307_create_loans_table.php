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
        Schema::create('loans', function (Blueprint $table) {
            $table->string('l_code', 20)->primary();
            $table->string('l_e_code', 20);
            $table->date('l_date');
            $table->date('l_return_date_plan');
            $table->string('l_status', 20)->default('pending');
            $table->text('l_note')->nullable();
            $table->timestamps();

            $table->foreign('l_e_code')
                ->references('e_code')
                ->on('master_employee')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
