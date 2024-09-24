<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('myscheduler', function () {
    Artisan::call('jadwal:check');
})->purpose('Mengirim notifikasi ke wa')->everyMinute();
