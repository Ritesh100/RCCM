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
            $table->decimal('remaining_sick_leave', 8, 2)->default(0);
            $table->decimal('remaining_annual_leave', 8, 2)->default(0);
            $table->decimal('remaining_public_holiday', 8, 2)->default(0);
            $table->decimal('remaining_unpaid_leave', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn([
                'remaining_sick_leave',
                'remaining_annual_leave',
                'remaining_public_holiday',
                'remaining_unpaid_leave'
            ]);
        });
    }
};
