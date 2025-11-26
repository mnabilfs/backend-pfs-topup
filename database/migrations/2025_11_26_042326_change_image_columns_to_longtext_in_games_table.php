<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->longText('image_url')->change();
            $table->longText('banner_url')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->longText('image_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->text('image_url')->change();
            $table->text('banner_url')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });
    }
};
