<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_rooms_table.php
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Ruang A101"
            // Building id
            $table->foreignId('building_id')->constrained()->onDelete('cascade'); // Relasi ke gedung
            $table->string('device_id')->unique()->nullable(); // ID unik untuk ESP8266 di pintu
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
