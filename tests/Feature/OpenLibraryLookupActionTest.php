<?php

namespace Tests\Feature;

use App\Filament\Resources\BookResource\Pages\CreateBook;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class OpenLibraryLookupActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_isbn_lookup_action_fills_the_book_form(): void
    {
        Http::fake([
            'openlibrary.org/isbn/*.json' => Http::response([
                'title' => 'Atomic Habits',
                'authors' => [
                    ['key' => '/authors/OL1234567A'],
                ],
                'description' => 'Panduan membangun kebiasaan baik.',
            ]),
            'openlibrary.org/authors/*.json' => Http::response([
                'name' => 'James Clear',
            ]),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        Livewire::test(CreateBook::class)
            ->fillForm(['isbn' => '9786020407191'])
            ->callFormComponentAction('isbn', 'lookupIsbn')
            ->assertHasNoFormErrors()
            ->assertFormSet([
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'description' => 'Panduan membangun kebiasaan baik.',
                'cover_url' => ['https://covers.openlibrary.org/b/isbn/9786020407191-L.jpg'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Book::class, [
            'isbn' => '9786020407191',
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'cover_url' => 'https://covers.openlibrary.org/b/isbn/9786020407191-L.jpg',
        ]);
    }

    public function test_isbn_lookup_clears_stale_author_and_description(): void
    {
        Http::fake([
            'openlibrary.org/isbn/*.json' => Http::response([
                'title' => 'Steve Jobs',
                'description' => 'Biografi Steve Jobs.',
            ]),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        Livewire::test(CreateBook::class)
            ->fillForm([
                'isbn' => '9781451648539',
                'author' => 'J.D. Salinger',
                'description' => 'Deskripsi lama',
                'cover_url' => ['https://example.com/old-cover.jpg'],
            ])
            ->callFormComponentAction('isbn', 'lookupIsbn')
            ->assertHasNoFormErrors()
            ->assertFormSet([
                'title' => 'Steve Jobs',
                'author' => '',
                'description' => 'Biografi Steve Jobs.',
                'cover_url' => ['https://covers.openlibrary.org/b/isbn/9781451648539-L.jpg'],
            ]);
    }
}