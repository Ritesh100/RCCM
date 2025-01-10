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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('rccPartner_tbl')->onDelete('cascade');
            $table->string('reportingTo');         
            $table->string('week_range');         
            $table->decimal('gross_earning', 10, 2)->default(0.00);
            $table->string('hrs_worked');
            $table->string('hrlyRate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
