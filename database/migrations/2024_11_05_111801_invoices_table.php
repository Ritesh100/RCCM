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
            $table->string('rc_partner_id')->constrained('rccpartner_tbl')->onDelete('cascade');
            $table->string('week_range');
            $table->string('invoice_for');
            $table->string('email');
            $table->string('invoice_from');
            $table->string('invoice_number');
            $table->string('total_charge');
            $table->string('total_transferred'); 
            $table->string('previous_credits'); 
            $table->string('charge_name');
            $table->string('charge_total');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
