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
        Schema::table('image_posts', function (Blueprint $table) {
            $table->string('danbooru_url')->nullable()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_posts', function (Blueprint $table) {
            $table->string('danbooru_url')->nullable()->change();
            $table->dropUnique('image_posts_danbooru_url_unique');
        });
    }
};
