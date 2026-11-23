<?php

namespace Tests\Feature;

use App\Mail\DueDateReminder;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendDueDateRemindersTest extends TestCase
{
    use RefreshDatabase;

    private function makeBook(string $title = 'Buku Uji'): Book
    {
        return Book::create([
            'title' => $title,
            'author' => 'Penulis Uji',
            'isbn' => '978-0-0000-0000-' . fake()->unique()->numerify('####'),
            'description' => 'Deskripsi uji',
            'total_copies' => 1,
            'available_copies' => 1,
            'file_url' => 'books/test.pdf',
            'cover_url' => null,
        ]);
    }

    private function makeBorrowing(User $user, Book $book, ?string $dueAt, ?string $returnedAt = null): Borrowing
    {
        return Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now()->subDays(6),
            'due_at' => $dueAt,
            'returned_at' => $returnedAt,
            'extended' => false,
            'fine_amount' => 0,
        ]);
    }

    public function test_it_sends_reminder_for_books_due_tomorrow(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $borrowing = $this->makeBorrowing($user, $this->makeBook(), now()->addDay());

        $this->artisan('pustaka:send-reminders')->assertSuccessful();

        Mail::assertSentCount(1);
        Mail::assertSent(DueDateReminder::class, fn ($mail) => $mail->borrowing->is($borrowing));
    }

    public function test_it_ignores_books_not_due_tomorrow(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $this->makeBorrowing($user, $this->makeBook('Jatuh Tempo 3 Hari'), now()->addDays(3));
        $this->makeBorrowing($user, $this->makeBook('Sudah Lewat'), now()->subDay());

        $this->artisan('pustaka:send-reminders')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_it_ignores_books_already_returned(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $this->makeBorrowing($user, $this->makeBook(), now()->addDay(), now());

        $this->artisan('pustaka:send-reminders')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_reminder_email_contains_borrowing_details(): void
    {
        $user = User::factory()->create(['name' => 'Budi']);
        $book = $this->makeBook('Atomic Habits');
        $borrowing = $this->makeBorrowing($user, $book, now()->addDay());

        $html = (new DueDateReminder($borrowing))->render();

        $this->assertStringContainsString('Budi', $html);
        $this->assertStringContainsString('Atomic Habits', $html);
        $this->assertStringContainsString($borrowing->due_at->isoFormat('D MMM YYYY'), $html);
    }
}