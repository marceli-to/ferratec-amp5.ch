<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Writes public/robots.txt for the current environment. Runs on every composer install (see composer.json),
 * so each server gets the right file: production allows crawling and points to the sitemap, all others block everything.
 * A static file is used because the usual nginx configs (Forge, Herd) never pass /robots.txt on to Laravel.
 */
Artisan::command('seo:robots', function () {
    $cp = '/'.trim(config('statamic.cp.route', 'cp'), '/');

    $robots = app()->isProduction()
        ? "User-agent: *\nDisallow: {$cp}\n\nSitemap: ".url('sitemap.xml')."\n"
        : "User-agent: *\nDisallow: /\n";

    file_put_contents(public_path('robots.txt'), $robots);

    $this->info('robots.txt written for '.app()->environment().'.');
})->purpose('Write public/robots.txt for the current environment');
