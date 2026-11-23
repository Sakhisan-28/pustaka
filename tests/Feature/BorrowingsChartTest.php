<?php

namespace Tests\Feature;

use App\Filament\Widgets\BorrowingsChart;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BorrowingsChartTest extends TestCase
{
    use RefreshDatabase;

    public function test_chart_widget_renders_with_borrowings_data(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'Buku Uji',
            'author' => 'Penulis Uji',
            'isbn' => '9780000000001',
            'total_copies' => 1,
            'available_copies' => 1,
        ]);

        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'due_at' => now()->addDays(7),
            'returned_at' => null,
            'extended' => false,
            'fine_amount' => 0,
        ]);

        Livewire::test(BorrowingsChart::class)
            ->assertStatus(200);
    }
}