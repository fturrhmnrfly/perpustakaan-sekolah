<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowingReturnApprovedEmailNotification extends Notification
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
        $plannedDate = optional($this->borrowing->tanggal_kembali_rencana)->format('d M Y');
        $actualDate = optional($this->borrowing->tanggal_kembali_aktual)->format('d M Y H:i');
        $condition = ucfirst((string) $this->borrowing->kondisi_kembali);

        return (new MailMessage)
            ->subject('Pengembalian Buku Disetujui Admin')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Pengembalian buku kamu telah disetujui oleh admin.')
            ->line('Judul Buku: '.$bookTitle)
            ->line('Rencana Tanggal Kembali: '.($plannedDate ?: '-'))
            ->line('Tanggal Diproses Admin: '.($actualDate ?: '-'))
            ->line('Kondisi Buku Saat Kembali: '.($condition ?: '-'))
            ->line('Terima kasih sudah menggunakan layanan perpustakaan dengan tertib.');
    }
}
