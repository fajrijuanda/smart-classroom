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
        // Tabel hari libur
        Schema::create('academic_calendar_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_calendar_id')->constrained()->onDelete('cascade');
            $table->date('holiday_date');
            $table->string('name'); // Nama hari libur
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendar_holidays');
    }
};
