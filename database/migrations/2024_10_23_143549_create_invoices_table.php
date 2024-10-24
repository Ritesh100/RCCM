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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rc_partner_id')->constrained('rccPartner_tbl')->onDelete('cascade');
            $table->string('week_range');
            $table->string('invoice_for');
            $table->string('email');
            $table->string('invoice_from');
            $table->string('invoice_number');
            $table->decimal('total_charge', 10, 6)->default(0.000000);
            $table->decimal('total_transferred', 10, 6)->default(0.000000);
            $table->decimal('previous_credits', 10, 6)->default(0.000000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
