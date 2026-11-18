<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'total_copies',
        'available_copies',
        'category_id',
        'file_url',
        'cover_url',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function readingProgresses(): HasMany
    {
        return $this->hasMany(ReadingProgress::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Filter katalog: search (judul/penulis/ISBN/kategori), filter kategori,
     * filter ketersediaan, dan sorting.
     */
    public function scopeFilter($query, array $filters)
    {
        $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'ilike', "%{$search}%")
                        ->orWhere('author', 'ilike', "%{$search}%")
                        ->orWhere('isbn', 'ilike', "%{$search}%")
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'ilike', "%{$search}%"));
                });
            })
            ->when($filters['category'] ?? null, fn ($q, $category) => $q->whereHas(
                'category',
                fn ($c) => $c->where('slug', $category)
            ))
            ->when($filters['available'] ?? false, fn ($q) => $q->where('available_copies', '>', 0))
            ->when($filters['sort'] ?? null, fn ($q, $sort) => match ($sort) {
                'title' => $q->orderBy('title'),
                'popular' => $q->withCount('borrowings')->orderByDesc('borrowings_count'),
                default => $q->latest(),
            }, fn ($q) => $q->latest());
    }

    public function borrows(User $user): bool
    {
        return $this->borrowings()
            ->where('user_id', $user->id)
            ->whereNull('returned_at')
            ->exists();
    }

    /**
     * Apakah buku masih tersedia untuk dipinjam.
     */
    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }

    public function coverUrl(): ?string
    {
        if (! $this->cover_url) {
            return null;
        }

        return filter_var($this->cover_url, FILTER_VALIDATE_URL)
            ? $this->cover_url
            : Storage::disk('public')->url($this->cover_url);
    }

    /**
     * Label status ketersediaan untuk ditampilkan.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->isAvailable() ? 'Tersedia' : 'Stok Habis';
    }
}