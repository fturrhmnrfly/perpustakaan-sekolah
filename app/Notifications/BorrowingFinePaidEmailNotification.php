<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowingFinePaidEmailNotification extends Notification
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

        $fineType = [];
        if ($lateFine > 0) {
            $fineType[] = 'Keterlambatan';
        }
        if ($damageFine > 0) {
            $fineType[] = 'Kerusakan/Kehilangan';
        }

        $mailMessage = (new MailMessage)
            ->subject('Pelunasan Denda Berhasil')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Pembayaran denda kamu sudah kami terima dan status transaksi dinyatakan lunas.')
            ->line('Judul Buku: '.$bookTitle)
            ->line('Jenis Denda: '.(! empty($fineType) ? implode(' + ', $fineType) : 'Denda umum'))
            ->line('Denda Keterlambatan: Rp '.number_format($lateFine, 0, ',', '.'))
            ->line('Denda Kerusakan/Kehilangan: Rp '.number_format($damageFine, 0, ',', '.'))
            ->line('Total Denda Lunas: Rp '.number_format($totalFine, 0, ',', '.'))
            ->action('Lihat Riwayat Peminjaman', route('borrowing.history'))
            ->line('Terima kasih, tetap jaga buku perpustakaan dengan baik.');

        if ($adminNote !== '') {
            $mailMessage->line('Keterangan Admin: '.$adminNote);
        }

        return $mailMessage;
    }
}
