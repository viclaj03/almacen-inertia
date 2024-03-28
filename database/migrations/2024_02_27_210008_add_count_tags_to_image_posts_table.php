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
            $table->integer('tag_count')->default(0);
            $table->integer('tag_count_general')->default(0);
            $table->integer('tag_count_artist')->default(0);
            $table->integer('tag_count_character')->default(0);
            $table->integer('tag_count_copyright')->default(0);
            $table->integer('tag_count_meta')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_posts', function (Blueprint $table) {
            $table->dropColumn('tag_count');
            $table->dropColumn('tag_count_general');
            $table->dropColumn('tag_count_artist');
            $table->dropColumn('tag_count_character');
            $table->dropColumn('tag_count_copyright');
            $table->dropColumn('tag_count_meta');
        });
    }
};
