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
        Schema::table('company_tbl', function (Blueprint $table) {
            $table->string('contact_person')->nullable();
            $table->string('master_agreement_path')->nullable();
            $table->string('service_agreement_path')->nullable();
            $table->text('service_schedule_path')->nullable();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_tbl', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person',
                'master_agreement_path',
                'service_agreement_path',
                'service_schedule_path',
            ]);
        });
    }
};
