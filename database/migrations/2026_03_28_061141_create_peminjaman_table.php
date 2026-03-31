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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();

            // RELASI KE USERS
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // RELASI KE BUKU
            $table->foreignId('buku_id')
                ->constrained('bukus')
                ->cascadeOnDelete();

            // TANGGAL
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali')->nullable(); // boleh kosong kalau belum balik

            // STATUS
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
