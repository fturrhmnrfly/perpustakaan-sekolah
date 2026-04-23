<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowingDueSoonReminderEmailNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Borrowing $borrowing
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $bookTitle = $this->borrowing->book?->judul ?? '-';
        $dueDate = Carbon::parse($this->borrowing->tanggal_kembali_rencana);
        $daysLeft = max(0, Carbon::today()->diffInDays($dueDate, false));

        return (new MailMessage)
            ->subject('Pengingat Pengembalian Buku')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Masa peminjaman buku kamu akan segera berakhir.')
            ->line('Judul Buku: '.$bookTitle)
            ->line('Batas Tanggal Kembali: '.$dueDate->format('d M Y'))
            ->line('Sisa Hari: '.$daysLeft.' hari')
            ->action('Ajukan Pengembalian Buku', route('borrowing.returns'))
            ->line('Mohon segera kembalikan buku agar terhindar dari denda keterlambatan.');
    }
}
