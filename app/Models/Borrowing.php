<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'menunggu_persetujuan';
    public const STATUS_ACTIVE = 'aktif';
    public const STATUS_RETURN_PENDING = 'menunggu_pengembalian';
    public const STATUS_WAITING_PAYMENT = 'menunggu_pembayaran';
    public const STATUS_RETURNED = 'dikembalikan';
    public const STATUS_REJECTED = 'ditolak';
    public const STATUS_LOST = 'hilang';

    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_peminjaman',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'kondisi_kembali',
        'keterangan',
        'denda',
        'denda_keterlambatan',
        'denda_kerusakan',
        'fine_payment_status',
        'fine_payment_method',
        'fine_payment_note',
        'fine_paid_at',
        'due_reminder_sent_at',
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'datetime',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'datetime',
        'fine_paid_at' => 'datetime',
        'due_reminder_sent_at' => 'datetime',
    ];

    /**
     * Get the user who borrowed the book
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Calculate fine for overdue book
     */
    public function calculateFine()
    {
        if (in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_RETURN_PENDING], true) && now()->toDateString() > $this->tanggal_kembali_rencana) {
            $daysLate = Carbon::now()->diffInDays(Carbon::parse($this->tanggal_kembali_rencana));
            return $daysLate * 5000; // 5000 per hari
        }
        return 0;
    }

    /**
     * Check if borrowing is overdue
     */
    public function isOverdue()
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_RETURN_PENDING], true)
            && now()->toDateString() > $this->tanggal_kembali_rencana;
    }

    /**
     * Get days until return
     */
    public function daysUntilReturn()
    {
        return now()->diffInDays($this->tanggal_kembali_rencana, false);
    }
}
