<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kalender akademik, misalnya "Kalender Akademik 2025"
            $table->date('start_date'); // Tanggal mulai kalender akademik
            $table->date('end_date'); // Tanggal akhir kalender akademik
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade'); // Tahun ajaran terkait
            $table->foreignId('semester_id')->constrained()->onDelete('cascade'); // Semester terkait
            $table->string('description')->nullable(); // Deskripsi kalender akademik
            $table->boolean('is_current')->default(false); // Menandakan apakah ini kalender akademik yang sedang berlaku
            $table->boolean('is_active')->default(true); // Menandakan apakah kalender akademik ini aktif
            $table->boolean('is_published')->default(false); // Menandakan apakah kalender akademik ini sudah dipublikasikan
            $table->timestamps();
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
