<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UpdateBookCoversTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_only_books_without_cover_url(): void
    {
        Http::fake([
            'covers.openlibrary.org/*' => Http::response('', 200),
        ]);

        $bookWithoutCover = Book::create([
            'title' => 'Book Without Cover',
            'author' => 'Author',
            'isbn' => '9781451648539',
            'total_copies' => 1,
            'available_copies' => 1,
            'cover_url' => null,
        ]);

        $bookWithCover = Book::create([
            'title' => 'Book With Cover',
            'author' => 'Author',
            'isbn' => '9780735211292',
            'total_copies' => 1,
            'available_copies' => 1,
            'cover_url' => 'https://example.com/existing.jpg',
        ]);

        $bookWithoutIsbn = Book::create([
            'title' => 'Book Without ISBN',
            'author' => 'Author',
            'isbn' => null,
            'total_copies' => 1,
            'available_copies' => 1,
            'cover_url' => '',
        ]);

        $this->artisan('books:update-covers')
            ->expectsOutput('Updated cover for book: Book Without Cover')
            ->assertSuccessful();

        $this->assertSame(
            'https://covers.openlibrary.org/b/isbn/9781451648539-L.jpg',
            $bookWithoutCover->fresh()->cover_url,
        );
        $this->assertSame('https://example.com/existing.jpg', $bookWithCover->fresh()->cover_url);
        $this->assertSame('', $bookWithoutIsbn->fresh()->cover_url);
    }

    public function test_it_does_not_save_cover_when_url_is_not_available(): void
    {
        Http::fake([
            'covers.openlibrary.org/*' => Http::response('', 404),
        ]);

        $book = Book::create([
            'title' => 'Book Without Cover',
            'author' => 'Author',
            'isbn' => '9781451648539',
            'total_copies' => 1,
            'available_copies' => 1,
            'cover_url' => null,
        ]);

        $this->artisan('books:update-covers')
            ->expectsOutput('Cover not found: Book Without Cover')
            ->assertSuccessful();

        $this->assertNull($book->fresh()->cover_url);
    }
}