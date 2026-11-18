<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenLibraryService
{
    private const BASE_URL = 'https://openlibrary.org';

    /**
     * Cari metadata buku berdasarkan ISBN.
     *
     * @return array{title: ?string, author: ?string, description: ?string, cover_url: ?string}|null
     */
    public function findByIsbn(string $isbn): ?array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->get(self::BASE_URL . "/isbn/{$isbn}.json");

        if (! $response->successful()) {
            return null;
        }

        $book = $response->json();

        if (! is_array($book)) {
            return null;
        }

        $description = data_get($book, 'description');

        if (is_array($description)) {
            $description = $description['value'] ?? null;
        }

        return [
            'title' => is_string($book['title'] ?? null) ? $book['title'] : null,
            'author' => $this->resolveAuthors($book),
            'description' => is_string($description) ? $description : null,
            'cover_url' => "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg",
        ];
    }

    /**
     * @param  array<string, mixed>  $book
     */
    private function resolveAuthors(array $book): ?string
    {
        $names = [];

        foreach ($this->authorKeys($book['authors'] ?? []) as $key) {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get(self::BASE_URL . '/authors/' . basename($key) . '.json');

            if (! $response->successful()) {
                continue;
            }

            $name = data_get($response->json(), 'name');

            if (is_string($name) && trim($name) !== '') {
                $names[] = $this->normalizeAuthorName($name);
            }
        }

        if ($names !== []) {
            return implode(', ', array_unique($names));
        }

        foreach ($this->authorNames($book['author'] ?? []) as $name) {
            $names[] = $this->normalizeAuthorName($name);
        }

        return $names === [] ? null : implode(', ', array_unique($names));
    }

    /**
     * @param  mixed  $authors
     * @return array<string>
     */
    private function authorKeys(mixed $authors): array
    {
        if (! is_array($authors)) {
            return [];
        }

        $keys = [];

        foreach ($authors as $author) {
            if (is_string($author) && preg_match('~(?:^|/)OL\\d+A$~', $author)) {
                $keys[] = $author;
                continue;
            }

            if (! is_array($author)) {
                continue;
            }

            $key = $author['key'] ?? null;

            if (! is_string($key) || trim($key) === '') {
                continue;
            }

            $path = parse_url($key, PHP_URL_PATH);
            $id = basename($path ?? $key);

            if ($id !== '') {
                $keys[] = $id;
            }
        }

        return array_values(array_unique($keys));
    }

    /**
     * @param  mixed  $authors
     * @return array<string>
     */
    private function authorNames(mixed $authors): array
    {
        if (is_string($authors)) {
            $authors = [$authors];
        }

        if (! is_array($authors)) {
            return [];
        }

        $names = [];

        foreach ($authors as $author) {
            if (is_string($author) && trim($author) !== '') {
                $names[] = $author;
            }
        }

        return $names;
    }

    private function normalizeAuthorName(string $name): string
    {
        $name = trim($name);

        if (str_contains($name, ',')) {
            [$last, $first] = array_pad(explode(',', $name, 2), 2, '');
            $name = trim(rtrim(trim($first), '.') . ' ' . trim($last));
        }

        return rtrim($name, '.');
    }
}