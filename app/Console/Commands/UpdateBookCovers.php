<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateBookCovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:update-covers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update missing book covers from Open Library by ISBN';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $books = Book::query()
            ->where(function ($query) {
                $query->whereNull('cover_url')
                    ->orWhere('cover_url', '');
            })
            ->get();

        if ($books->isEmpty()) {
            $this->info('All books already have a cover URL.');

            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;

        foreach ($books as $book) {
            $isbn = strtoupper((string) preg_replace('/[^0-9Xx]/', '', (string) $book->isbn));

            if ($isbn === '') {
                $this->warn("Skipped (no ISBN): {$book->title}");
                $skipped++;

                continue;
            }

            $url = "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg";

            try {
                $response = Http::timeout(10)
                    ->withOptions(['allow_redirects' => true])
                    ->head($url);

                if (! $response->successful()) {
                    $this->warn("Cover not found: {$book->title}");

                    $skipped++;

                    continue;
                }
            } catch (\Throwable $e) {
                $this->warn("Failed to check cover: {$book->title}");

                $skipped++;

                continue;
            }

            $book->cover_url = $url;
            $book->save();

            $this->info("Updated cover for book: {$book->title}");

            $updated++;
        }

        $this->info("Done. Updated: {$updated}, skipped: {$skipped}.");

        return self::SUCCESS;
    }
}