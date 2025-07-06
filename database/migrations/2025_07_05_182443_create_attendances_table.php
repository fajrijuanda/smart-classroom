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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Mahasiswa yang hadir
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade'); // Jadwal kelas yang dihadiri
            $table->timestamp('recorded_at'); // Waktu presensi direkam oleh ESP32
            // Status
            $table->enum('status', ['present', 'absent', 'late'])->default('present'); // Status kehadiran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
