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
        Schema::create('image_posts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('original_url')->nullable();
            $table->string('danbooru_url')->nullable();
            $table->foreignId('user_post')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->string('imagen');
            $table->boolean('pegi_18');
            $table->boolean('private')->default('0');
            $table->string('file_ext');
            $table->integer('file_size');
            $table->string('imagen_hash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
