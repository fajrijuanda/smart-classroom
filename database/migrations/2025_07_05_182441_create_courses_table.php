<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_courses_table.php
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: "IF201"
            $table->string('name'); // Contoh: "Dasar Pemrograman"
            // capacity
            $table->integer('capacity')->default(40); // Kapasitas kelas, default 30
            $table->foreignId('lecturer_id')->constrained('users'); // Relasi ke dosen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
