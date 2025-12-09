<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sold_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');           // nama akun (contoh: Akun ML Epic 500 Skin)
            $table->text('description');       // deskripsi lengkap
            $table->integer('price');          // harga dalam Rupiah
            $table->longtext('image_url');       // gambar utama
            $table->json('gallery')->nullable(); // optional: array foto lain
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_accounts');
    }
};
