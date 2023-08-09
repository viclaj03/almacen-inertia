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
        Schema::table('image_post', function (Blueprint $table) {
            // Cambiar imagen_hash a nullable
        Schema::table('image_posts', function (Blueprint $table) {
            $table->string('imagen_hash')->nullable()->change();
        });

        // Crear nueva columna para la versión más ligera de la imagen
        Schema::table('image_posts', function (Blueprint $table) {
            $table->string('light_version_imagen')->nullable();
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_post', function (Blueprint $table) {
            //
        });
    }
};
