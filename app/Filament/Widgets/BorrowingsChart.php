<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Filament\Widgets\ChartWidget;

class BorrowingsChart extends ChartWidget
{
    protected static ?string $heading = 'Peminjaman 7 Hari Terakhir';

    protected static ?int $sort = 2;

    protected static string $color = 'primary';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $start = now()->subDays(6)->startOfDay();

        $counts = Borrowing::query()
            ->where('borrowed_at', '>=', $start)
            ->where('borrowed_at', '<=', now()->endOfDay())
            ->selectRaw('DATE(borrowed_at) as date')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $labels = [];
        $data = [];

        for ($offset = 0; $offset < 7; $offset++) {
            $day = $start->copy()->addDays($offset);
            $key = $day->format('Y-m-d');

            $labels[] = $day->translatedFormat('d M');
            $data[] = (int) ($counts[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Peminjaman',
                    'data' => $data,
                    'backgroundColor' => '#3B82F6',
                    'borderColor' => '#3B82F6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}