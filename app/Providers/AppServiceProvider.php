<?php

namespace App\Providers;

use App\Models\lending_program_tbl;
use App\Observers\LendingProgramObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        lending_program_tbl::observe(LendingProgramObserver::class);
    }
}