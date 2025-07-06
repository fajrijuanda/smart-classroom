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
        // Tabel pertemuan
        Schema::create('academic_calendar_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_calendar_id')->constrained()->onDelete('cascade');
            $table->integer('meeting_number'); // 1-16
            $table->date('meeting_date');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendar_meetings');
    }
};
