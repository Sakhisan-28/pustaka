<?php

namespace App\Console\Commands;

use App\Mail\DueDateReminder;
use App\Models\Borrowing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDueDateReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pustaka:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email pengingat untuk buku yang jatuh tempo besok';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $borrowings = Borrowing::query()
            ->with(['user', 'book'])
            ->whereNull('returned_at')
            ->whereDate('due_at', now()->addDay()->toDateString())
            ->get();

        if ($borrowings->isEmpty()) {
            $this->info('Tidak ada buku yang jatuh tempo besok.');

            return self::SUCCESS;
        }

        $sent = 0;
        $failed = 0;

        foreach ($borrowings as $borrowing) {
            try {
                Mail::to($borrowing->user->email)->send(new DueDateReminder($borrowing));

                $this->info("Reminder terkirim ke {$borrowing->user->email}: {$borrowing->book->title}");

                $sent++;
            } catch (\Throwable $e) {
                $this->error("Gagal mengirim ke {$borrowing->user->email}: {$e->getMessage()}");

                $failed++;
            }
        }

        $this->info("Selesai. Terkirim: {$sent}, gagal: {$failed}.");

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}