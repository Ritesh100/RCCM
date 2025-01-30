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
        Schema::table('leaves', function (Blueprint $table) {
            $table->decimal('sick_leave_taken', 10, 2)->change();
            $table->decimal('public_holiday_taken', 10, 2)->change();
            $table->decimal('taken_unpaid_leave', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('sick_leave_taken')->change();
            $table->integer('public_holiday_taken')->change();
            $table->integer('taken_unpaid_leave')->change();
        });
    }
};
