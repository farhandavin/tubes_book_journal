<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            // Kolom untuk menyimpan hasil: POSITIF / NETRAL / NEGATIF
            $table->string('sentiment')->nullable()->after('notes');
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('sentiment');
        });
    }
};