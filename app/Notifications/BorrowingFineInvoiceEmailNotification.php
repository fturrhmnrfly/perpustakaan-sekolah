<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowingFineInvoiceEmailNotification extends Notification
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
        $lateFine = (float) ($this->borrowing->denda_keterlambatan ?? 0);
        $damageFine = (float) ($this->borrowing->denda_kerusakan ?? 0);
        $totalFine = (float) ($this->borrowing->denda ?? 0);
        $adminNote = trim((string) ($this->borrowing->keterangan ?? ''));
        $damageNote = $damageFine > 0
            ? ($adminNote !== '' ? $adminNote : 'Ada kerusakan/kehilangan pada buku.')
            : 'Tidak ada.';

        $mailMessage = (new MailMessage)
            ->subject('Tagihan Denda Peminjaman Buku')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Terdapat tagihan denda pada transaksi peminjaman buku kamu.')
            ->line('Judul Buku: '.$bookTitle)
            ->line('Denda Keterlambatan: Rp '.number_format($lateFine, 0, ',', '.'))
            ->line('Denda Kerusakan/Kehilangan: Rp '.number_format($damageFine, 0, ',', '.'))
            ->line('Keterangan Kerusakan/Kehilangan: '.$damageNote)
            ->line('Total Tagihan Denda: Rp '.number_format($totalFine, 0, ',', '.'))
            ->line('Segera lakukan pembayaran.');

        if ($lateFine > 0 && $damageFine <= 0 && $adminNote !== '') {
            $mailMessage->line('Catatan Admin: '.$adminNote);
        }

        return $mailMessage;
    }
}
