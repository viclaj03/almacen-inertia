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
        Schema::create('tags_post', function (Blueprint $table) {
           $table->foreignId('image_id')->references('id')->on('image_posts')->onDelete('cascade');
           $table->foreignId('tag_id')->references('id')->on('tags')->onDelete('cascade');
           $table->primary(['image_id','tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
