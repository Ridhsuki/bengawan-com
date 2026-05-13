<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('shopee:refresh-tokens')
    ->everyThreeHours()
    ->withoutOverlapping();

Schedule::command('shopee:pull-orders --hours=24')
    ->everyTenMinutes()
    ->withoutOverlapping();

Schedule::command('shopee:reconcile-products')
    ->hourly()
    ->withoutOverlapping();
