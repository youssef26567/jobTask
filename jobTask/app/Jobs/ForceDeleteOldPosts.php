<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ForceDeleteOldPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        // You can pass parameters to the constructor if needed
    }

    public function handle()
    {
        // Get the date 30 days ago
        $dateThreshold = now()->subDays(30);

        // Force delete posts that are softly deleted and older than 30 days
        Post::onlyTrashed()
            ->where('deleted_at', '<=', $dateThreshold)
            ->forceDelete();
    }
}
