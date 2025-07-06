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
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama tahun ajaran, misalnya "202501" adalah Semester ganjil tahun 2025
            $table->date('start_date'); // Tanggal mulai tahun ajaran
            $table->date('end_date'); // Tanggal akhir tahun ajaran
            $table->boolean('is_current')->default(false); // Menandakan apakah ini Tahun
            // Semeseter id
            $table->foreignId('semester_id')->constrained()->onDelete('cascade'); // Semester terkait
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
