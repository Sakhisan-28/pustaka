<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Kolom category_id seharusnya nullable (buku boleh tanpa kategori),
     * tapi foreignId() bawaan Laravel membuatnya NOT NULL.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE books ALTER COLUMN category_id DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE books ALTER COLUMN category_id SET NOT NULL');
    }
};