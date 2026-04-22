<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'isbn',
        'category_id',
        'tahun_terbit',
        'stok',
        'stok_tersedia',
        'deskripsi',
        'kondisi',
        'lokasi',
        'cover_image',
    ];

    protected $appends = [
        'cover_url',
    ];

    /**
     * Get the category this book belongs to
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all borrowings for this book
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Check if book is available
     */
    public function isAvailable()
    {
        return $this->stok_tersedia > 0;
    }

    /**
     * Get full URL for book cover.
     */
    public function getCoverUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/'.$this->cover_image);
        }

        return asset('images/book-placeholder.svg');
    }
}
