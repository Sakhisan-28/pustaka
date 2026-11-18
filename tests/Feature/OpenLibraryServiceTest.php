<?php

namespace Tests\Feature;

use App\Services\OpenLibraryService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenLibraryServiceTest extends TestCase
{
    public function test_it_returns_book_metadata_from_open_library(): void
    {
        Http::fake([
            'openlibrary.org/isbn/*.json' => Http::response([
                'title' => 'Atomic Habits',
                'authors' => [
                    ['key' => '/authors/OL1234567A'],
                ],
                'description' => [
                    'value' => 'Panduan membangun kebiasaan baik.',
                ],
            ]),
            'openlibrary.org/authors/*.json' => Http::response([
                'name' => 'James Clear',
            ]),
        ]);

        $data = app(OpenLibraryService::class)->findByIsbn('9786020407191');

        $this->assertSame('Atomic Habits', $data['title']);
        $this->assertSame('James Clear', $data['author']);
        $this->assertSame('Panduan membangun kebiasaan baik.', $data['description']);
        $this->assertSame('https://covers.openlibrary.org/b/isbn/9786020407191-L.jpg', $data['cover_url']);

        Http::assertSent(fn ($request) => $request->url() === 'https://openlibrary.org/isbn/9786020407191.json');
        Http::assertSent(fn ($request) => $request->url() === 'https://openlibrary.org/authors/OL1234567A.json');
    }

    public function test_it_normalizes_author_name_from_the_author_field(): void
    {
        Http::fake([
            'openlibrary.org/isbn/*.json' => Http::response([
                'title' => 'Steve Jobs',
                'author' => ['Isaacson, Walter.'],
                'description' => 'Biografi Steve Jobs.',
            ]),
        ]);

        $data = app(OpenLibraryService::class)->findByIsbn('9781451648539');

        $this->assertSame('Steve Jobs', $data['title']);
        $this->assertSame('Walter Isaacson', $data['author']);
        $this->assertSame('Biografi Steve Jobs.', $data['description']);
        $this->assertSame('https://covers.openlibrary.org/b/isbn/9781451648539-L.jpg', $data['cover_url']);

        Http::assertNotSent(fn ($request) => str_contains($request->url(), '/authors/'));
    }

    public function test_it_returns_null_when_book_is_not_found(): void
    {
        Http::fake([
            'openlibrary.org/isbn/*.json' => Http::response([], 404),
        ]);

        $this->assertNull(app(OpenLibraryService::class)->findByIsbn('0000000000'));
    }
}