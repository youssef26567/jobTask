<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ForceDeleteOldPosts;
use App\Jobs\FetchRandomUser;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Schedule the ForceDeleteOldPosts job to run daily
        $schedule->job(new ForceDeleteOldPosts())->daily();

        // Schedule the FetchRandomUser job to run every six hours
        $schedule->job(new FetchRandomUser())->everySixHours();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
