<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'rating',
        'notes',
        'date_read'
    ];
    
    // Casting agar date_read dibaca sebagai format tanggal oleh Carbon
    protected $casts = [
        'date_read' => 'date',
    ];
}
