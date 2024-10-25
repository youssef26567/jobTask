<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchRandomUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Make the HTTP request to the API
        $response = Http::get('https://randomuser.me/api/');

        // Check if the response was successful and has 'results'
        if ($response->successful() && isset($response['results'])) {
            // Log only the 'results' object from the response
            Log::info('Random User Data:', ['results' => $response['results']]);
        } else {
            Log::error('Failed to fetch data from Random User API.');
        }
    }
}
