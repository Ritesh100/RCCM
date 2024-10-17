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
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->string('cost_center');
            $table->date('date');
            $table->time('start_time');
            $table->time('close_time');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->string('timezone');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->string('user_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
