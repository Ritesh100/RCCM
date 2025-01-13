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
            // Change the default value of the 'total_unpaid_leave' column to 0
            $table->decimal('total_unpaid_leave', 5, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Revert back to the previous default value if needed
            $table->decimal('total_unpaid_leave', 5, 2)->default(0)->change();
        });
    }
};
