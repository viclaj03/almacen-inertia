<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->json('urls')->nullable()->change();


            DB::table('artists')->get()->each(function ($artist)  {
                if (!is_null($artist->urls)){

                    $urls_array = explode("\n",$artist->urls);

                    $urls_array = array_map('trim', $urls_array);

                    DB::table('artists')
                        ->where('id',$artist->id)
                        ->update(['urls' => json_encode($urls_array)]);
                }
                
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            //
        });
    }
};
