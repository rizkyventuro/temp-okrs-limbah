<?php

use Illuminate\Support\Facades\Schedule;

Schedule::useCache('redis');

Schedule::command('queue:cleanup')
    ->everyMinute()
    ->onOneServer()
    ->withoutOverlapping()
    ->runInBackground(false);
