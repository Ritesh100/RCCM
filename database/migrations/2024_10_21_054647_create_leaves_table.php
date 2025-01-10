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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('rccPartner_tbl')->onDelete('cascade');
            $table->Integer('total_sick_leave')->default(10);
            $table->Integer('total_annual_leave')->default(0);
            $table->Integer('sick_leave_taken')->default(0);
            $table->Integer('annual_leave_taken')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
