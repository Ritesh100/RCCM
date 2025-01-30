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
            // Drop columns if they exist
            if (Schema::hasColumn('leaves', 'sick_leave_taken')) {
                $table->dropColumn('sick_leave_taken');
            }
            if (Schema::hasColumn('leaves', 'public_holiday_taken')) {
                $table->dropColumn('public_holiday_taken');
            }
            if (Schema::hasColumn('leaves', 'taken_unpaid_leave')) {
                $table->dropColumn('taken_unpaid_leave');
            }

            $table->decimal('sick_leave_taken', 5, 2)->default(0.00);
            $table->decimal('public_holiday_taken', 5, 2)->default(0.00);
            $table->decimal('taken_unpaid_leave', 5, 2)->default(0.00);

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Remove the added columns
            $table->dropColumn([
                'sick_leave_taken',
                'public_holiday_taken',
                'taken_unpaid_leave',
            ]);
        });
    }
};
