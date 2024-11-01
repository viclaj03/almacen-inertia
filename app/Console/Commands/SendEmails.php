<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use App\Models\ImagePost;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'Send test emails';
    protected $signature = 'emails:send {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $image = ImagePost::inRandomOrder()->first();
        
        Mail::to(User::first()->email)->send(new TestEmail($image));
        $this->info('Test email sent to fastgalleryapi@gmail.com');
    }
}
