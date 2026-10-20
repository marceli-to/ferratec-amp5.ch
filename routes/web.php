<?php

use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);

Route::get('sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('llms.txt', [SeoController::class, 'llms']);
