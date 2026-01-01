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
        Schema::create('image_scans', function (Blueprint $table) {
            $table->id();

            $table->string('source', 50);          // ej: danbooru
            $table->unsignedBigInteger('source_id'); // post id

            $table->text('file_url');              // solo informativo
            $table->char('image_hash', 16);        // dHash / perceptual

            $table->timestamps();

            // Evita escanear dos veces el mismo post
            $table->unique(['source', 'source_id']);

            // Opcional, por si más adelante filtras por hash
            $table->index('image_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_scans');
    }
};
