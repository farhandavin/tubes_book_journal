<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Langsung didefinisikan di awal)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('category')->nullable(); // Langsung ada
            $table->string('isbn')->nullable();
            $table->integer('rating'); 
            $table->text('notes')->nullable();
            $table->string('sentiment')->nullable(); // Langsung ada
            $table->date('date_read')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};