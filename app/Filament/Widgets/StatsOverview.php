<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Buku', Book::count())
                ->description('Buku di katalog')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),
            Stat::make('Total Anggota', User::count())
                ->description('Pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Peminjaman Aktif', Borrowing::whereNull('returned_at')->count())
                ->description('Belum dikembalikan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
        ];
    }
}