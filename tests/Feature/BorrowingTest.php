<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Services\BorrowingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    private function makeBook(int $total = 7, int $available = 7): Book
    {
        return Book::create([
            'title' => 'Buku Uji',
            'author' => 'Penulis Uji',
            'isbn' => '978-0-0000-0000-' . fake()->unique()->numerify('####'),
            'description' => 'Deskripsi uji',
            'total_copies' => $total,
            'available_copies' => $available,
            'file_url' => 'books/test.pdf',
            'cover_url' => null,
        ]);
    }

    public function test_borrow_reduces_available_copies_and_creates_record(): void
    {
        $service = app(BorrowingService::class);
        $user = User::factory()->create();
        $book = $this->makeBook(7, 7);

        $borrowing = $service->borrow($user, $book);

        $this->assertSame(6, $book->fresh()->available_copies);
        $this->assertNotNull($borrowing->borrowed_at);
        $this->assertSame(now()->addDays(7)->format('Y-m-d H:i'), $borrowing->fresh()->due_at->format('Y-m-d H:i'));
        $this->assertNull($borrowing->returned_at);
    }

    public function test_borrow_rejects_duplicate_active_borrowing(): void
    {
        $service = app(BorrowingService::class);
        $user = User::factory()->create();
        $book = $this->makeBook(7, 7);

        $service->borrow($user, $book);

        $this->expectException(\RuntimeException::class);
        $service->borrow($user, $book);
    }

    public function test_borrow_rejects_when_out_of_stock(): void
    {
        $service = app(BorrowingService::class);
        $user = User::factory()->create();
        $book = $this->makeBook(1, 0);

        $this->expectException(\RuntimeException::class);
        $service->borrow($user, $book);
    }

    public function test_return_restores_stock_and_calculates_fine(): void
    {
        $service = app(BorrowingService::class);
        $user = User::factory()->create();
        $book = $this->makeBook(7, 7);

        $borrowing = $service->borrow($user, $book);

        // Simulate 2.5 hari keterlambatan
        $borrowing->due_at = now()->subDays(2)->subHours(12);
        $borrowing->save();

        $returned = $service->returnBook($borrowing);

        $this->assertSame(7, $book->fresh()->available_copies);
        $this->assertNotNull($returned->fresh()->returned_at);
        $this->assertSame(3000.0, (float) $returned->fresh()->fine_amount);
    }

    public function test_http_borrow_and_return_flow_with_flash_message(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook(7, 7);

        $response = $this->actingAs($user)->post("/pinjam/{$book->id}");
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame(6, $book->fresh()->available_copies);

        $borrowing = Borrowing::where('user_id', $user->id)->where('book_id', $book->id)->firstOrFail();

        $response = $this->actingAs($user)->post("/kembalikan/{$borrowing->id}");
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame(7, $book->fresh()->available_copies);

        $response = $this->actingAs($user)->get('/buku-saya');
        $response->assertOk();
    }
}