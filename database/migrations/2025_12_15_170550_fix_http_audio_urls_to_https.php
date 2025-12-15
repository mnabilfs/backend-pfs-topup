<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            UPDATE background_musics
            SET audio_url = REPLACE(audio_url, 'http://', 'https://')
            WHERE audio_url LIKE 'http://%'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE background_musics
            SET audio_url = REPLACE(audio_url, 'https://', 'http://')
            WHERE audio_url LIKE 'https://%'
        ");
    }
};
