<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'slug' => 'fiksi'],
            ['name' => 'Non-Fiksi', 'slug' => 'non-fiksi'],
            ['name' => 'Sejarah', 'slug' => 'sejarah'],
            ['name' => 'Sains', 'slug' => 'sains'],
            ['name' => 'Teknologi', 'slug' => 'teknologi'],
            ['name' => 'Biografi', 'slug' => 'biografi'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}