<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('books', function (Blueprint $table) {
        $table->id(); // Pengganti serial primary key
        $table->string('title');
        $table->string('author')->nullable();
        $table->string('isbn')->nullable();
        $table->integer('rating'); // Validasi 1-10 akan di Controller
        $table->text('notes')->nullable();
        $table->date('date_read')->nullable();
        $table->timestamps(); // Created_at & Updated_at otomatis
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
