<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Peminjam
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); // Buku yang dipinjam
            $table->date('borrowed_at'); // Tanggal pinjam
            $table->date('returned_at')->nullable(); // Tanggal kembali (jika null berarti belum kembali)
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowings');
    }
};