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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->string('nim'); // Nama lengkap pengguna
            $table->string('bio')->nullable(); // Biografi singkat
            $table->string('avatar')->nullable(); // URL atau path ke avatar pengguna
            $table->string('phone')->nullable(); // Nomor telepon pengguna
            $table->string('address')->nullable(); // Alamat pengguna
            $table->string('website')->nullable(); // URL situs web pengguna
            $table->string('social_links')->nullable(); // JSON atau string untuk menyimpan tautan sosial
            // array gambar muka saat pendaftaran ada 30
            // Misalnya, bisa disimpan sebagai JSON atau string terpisah
            $table->json('face_images')->nullable(); // Jika ingin menyimpan sebagai JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
