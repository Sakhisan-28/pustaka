<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['title' => 'Bumi', 'author' => 'Tere Liye', 'isbn' => '9786020633446', 'category' => 'fiksi', 'total' => 5, 'available' => 3, 'description' => 'Kisah petualangan Raib, Sela, dan Ali di dunia paralel.'],
            ['title' => 'Laut Bercerita', 'author' => 'Leila S. Chudori', 'isbn' => '9786024249108', 'category' => 'fiksi', 'total' => 4, 'available' => 4, 'description' => 'Novel tentang perjuangan aktivis mahasiswa di masa Orde Baru.'],
            ['title' => 'Cantik Itu Luka', 'author' => 'Eka Kurniawan', 'isbn' => '9789799731212', 'category' => 'fiksi', 'total' => 3, 'available' => 1, 'description' => 'Saga keluarga Indonesia yang magis dan kelam.'],
            ['title' => 'Sapiens: Riwayat Singkat Umat Manusia', 'author' => 'Yuval Noah Harari', 'isbn' => '9786020995827', 'category' => 'non-fiksi', 'total' => 6, 'available' => 5, 'description' => 'Perjalanan evolusi Homo sapiens dari masa lalu hingga kini.'],
            ['title' => 'Atomic Habits', 'author' => 'James Clear', 'isbn' => '9786020407191', 'category' => 'non-fiksi', 'total' => 8, 'available' => 6, 'description' => 'Panduan membangun kebiasaan baik dan menghilangkan kebiasaan buruk.'],
            ['title' => 'Filosofi Terap', 'author' => 'Henry Manampiring', 'isbn' => '9786028372537', 'category' => 'non-fiksi', 'total' => 5, 'available' => 0, 'description' => 'Menerapkan filsafat Stoa untuk mengelola emosi dan kecemasan.'],
            ['title' => 'Jejak-Jejak Sejarah Indonesia', 'author' => 'Ahmad Yani', 'isbn' => '9786021101234', 'category' => 'sejarah', 'total' => 3, 'available' => 2, 'description' => 'Rekonstruksi peristiwa penting dalam sejarah bangsa Indonesia.'],
            ['title' => 'Mata Uang & Peradaban', 'author' => 'Rizal Gunawan', 'isbn' => '9786029988776', 'category' => 'sejarah', 'total' => 2, 'available' => 2, 'description' => 'Bagaimana uang membentuk peradaban manusia.'],
            ['title' => 'Fisika untuk Semua', 'author' => 'Brian Greene', 'isbn' => '9786020334556', 'category' => 'sains', 'total' => 4, 'available' => 3, 'description' => 'Penjelasan menarik tentang hukum-hukum dasar alam semesta.'],
            ['title' => 'Otak Manusia dan Belajar', 'author' => 'Sarah Wijaya', 'isbn' => '9786025667788', 'category' => 'sains', 'total' => 3, 'available' => 3, 'description' => 'Bagaimana otak bekerja saat kita belajar hal baru.'],
            ['title' => 'Belajar Pemrograman Python', 'author' => 'Dedi Kurnia', 'isbn' => '9786028112233', 'category' => 'teknologi', 'total' => 7, 'available' => 7, 'description' => 'Panduan praktis memulai pemrograman dengan Python.'],
            ['title' => 'Kecerdasan Buatan untuk Pemula', 'author' => 'Maya Sari', 'isbn' => '9786028998877', 'category' => 'teknologi', 'total' => 5, 'available' => 4, 'description' => 'Pengantar konsep dasar kecerdasan buatan dan penerapannya.'],
            ['title' => 'B.J. Habibie: Dari Nol ke Angkasa', 'author' => 'Ridwan Nur', 'isbn' => '9786024556677', 'category' => 'biografi', 'total' => 3, 'available' => 1, 'description' => 'Perjalanan hidup B.J. Habibie, insinyur dan negarawan Indonesia.'],
            ['title' => 'Tan Malaka: Sang Pemikir', 'author' => 'Harry Roesli', 'isbn' => '9786023445566', 'category' => 'biografi', 'total' => 2, 'available' => 2, 'description' => 'Biografi pemikir dan pejuang kemerdekaan Indonesia.'],
        ];

        foreach ($books as $data) {
            $category = Category::where('slug', $data['category'])->first();

            Book::updateOrCreate(
                ['isbn' => $data['isbn']],
                [
                    'title' => $data['title'],
                    'author' => $data['author'],
                    'description' => $data['description'],
                    'total_copies' => $data['total'],
                    'available_copies' => $data['available'],
                    'category_id' => $category?->id,
                ]
            );
        }
    }
}