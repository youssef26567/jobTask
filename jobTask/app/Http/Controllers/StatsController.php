<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function index()
    {
        // Check if stats are cached; otherwise calculate and cache them
        $stats = Cache::remember('stats', 3600, function () {
            return [
                'total_users' => User::count(),
                'total_posts' => Post::count(),
                'users_with_no_posts' => User::doesntHave('posts')->count(),
            ];
        });

        return response()->json($stats);
    }
}
