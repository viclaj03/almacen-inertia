<?php

namespace App\Jobs;

use App\Models\ImagePost;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReviewTagsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       /* Tag::where('review', false)->chunk(10000, function ($tags) {
            foreach ($tags as $tag) {
                $tagName = str_replace(' ', '_', $tag->name);

                // Lógica para contar cuántos ImagePost contienen este tag
                $countImage = ImagePost::whereExists(function ($query) use ($tagName) {
                    $query->selectRaw(1)
                        ->whereRaw('LOWER(secondary_tags) LIKE ?', ['% ' . $tagName . ' %'])
                        ->orWhereRaw('LOWER(secondary_tags) LIKE ?', ['% ' . $tagName])
                        ->orWhereRaw('LOWER(secondary_tags) LIKE ?', [$tagName . ' %']);
                })->count();

                // Actualizar el tag
                $tag->post_count = $countImage;
                $tag->review = true;
                $tag->save();
            }
        });*/
    }
}
