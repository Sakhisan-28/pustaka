<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_search_matches_partial_title_author_or_isbn(): void
    {
        Book::create([
            'title' => 'Bumi',
            'author' => 'Tere Liye',
            'isbn' => '9786020633446',
            'total_copies' => 1,
            'available_copies' => 1,
        ]);

        Book::create([
            'title' => 'Laut Bercerita',
            'author' => 'Leila S. Chudori',
            'isbn' => '9786024249108',
            'total_copies' => 1,
            'available_copies' => 1,
        ]);

        $this->get('/katalog?search=bumi')
            ->assertOk()
            ->assertSee('Bumi')
            ->assertDontSee('Laut Bercerita');

        $this->get('/katalog?search=chudori')
            ->assertOk()
            ->assertSee('Laut Bercerita')
            ->assertDontSee('Bumi');

        $this->get('/katalog?search=6020633')
            ->assertOk()
            ->assertSee('Bumi')
            ->assertDontSee('Laut Bercerita');
    }
}