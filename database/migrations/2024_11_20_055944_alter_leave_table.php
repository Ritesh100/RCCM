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
            // Drop the columns if they already exist
            if (Schema::hasColumn('leaves', 'total_sick_leave')) {
                $table->dropColumn('total_sick_leave');
            }
            if (Schema::hasColumn('leaves', 'total_public_holiday')) {
                $table->dropColumn('total_public_holiday');
            }
            if (Schema::hasColumn('leaves', 'total_unpaid_leave')) {
                $table->dropColumn('total_unpaid_leave');
            }

            // Add the updated columns
            $table->decimal('total_sick_leave', 5, 2)->default(75.00);
            $table->decimal('total_public_holiday', 5, 2)->default(97.50);
            $table->decimal('total_unpaid_leave', 5, 2)->default(98.50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Remove the added columns
            $table->dropColumn('total_sick_leave');
            $table->dropColumn('total_public_holiday');
            $table->dropColumn('total_unpaid_leave');
        });
    }
};
