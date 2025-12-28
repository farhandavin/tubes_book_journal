<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'category',
        'isbn',
        'rating',
        'notes',
        'date_read',
        'sentiment',
        'stock',         // Tambahkan jika belum ada
        'cover_image'
    ];

    // Relasi ke peminjaman
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    // Cek apakah buku sedang dipinjam oleh siapapun
    public function isBorrowed()
    {
        return $this->borrowings()->where('status', 'dipinjam')->exists();
    }

    // Casting agar date_read dibaca sebagai format tanggal oleh Carbon
    protected $casts = [
        'date_read' => 'date',
    ];
}
