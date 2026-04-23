<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Notifications\BorrowingDueSoonReminderEmailNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBorrowingDueReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:send-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for borrowings that are close to due date';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();
        $reminderLimitDate = $today->copy()->addDays(2);

        $borrowings = Borrowing::query()
            ->with(['user', 'book'])
            ->where('status', Borrowing::STATUS_ACTIVE)
            ->whereDate('tanggal_kembali_rencana', '>=', $today->toDateString())
            ->whereDate('tanggal_kembali_rencana', '<=', $reminderLimitDate->toDateString())
            ->whereNull('due_reminder_sent_at')
            ->get();

        $sentCount = 0;

        foreach ($borrowings as $borrowing) {
            if (! $borrowing->user?->email) {
                continue;
            }

            $borrowing->user->notify(new BorrowingDueSoonReminderEmailNotification($borrowing));

            $borrowing->update([
                'due_reminder_sent_at' => now(),
            ]);

            $sentCount++;
        }

        $this->info("Reminder email terkirim: {$sentCount}");

        return self::SUCCESS;
    }
}
