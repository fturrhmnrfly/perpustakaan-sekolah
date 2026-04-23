<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BorrowingActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Borrowing $borrowing,
        private readonly string $title,
        private readonly string $message,
        private readonly ?string $targetUrl = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'borrowing_id' => $this->borrowing->id,
            'book_title' => $this->borrowing->book?->judul,
            'status' => $this->borrowing->status,
            'target_url' => $this->targetUrl ?? route('borrowing.history'),
        ];
    }
}
