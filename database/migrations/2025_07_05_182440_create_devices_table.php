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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama perangkat, misalnya "ESP32-001"
            $table->string('type'); // Jenis perangkat, misalnya "ESP32", "ESP8266", "Raspberry Pi"
            $table->string('mac_address')->unique(); // Alamat MAC unik untuk identifikasi
            $table->string('ip_address')->nullable(); // Alamat IP perangkat, bisa null jika belum terhubung
            $table->string('status')->default('offline'); // Status perangkat, default 'offline'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
