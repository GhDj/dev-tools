<?php

use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ToolController::class, 'index'])->name('home');

Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/csv', [ToolController::class, 'csv'])->name('csv');
    Route::get('/yaml', [ToolController::class, 'yaml'])->name('yaml');
    Route::get('/json', [ToolController::class, 'json'])->name('json');
    Route::get('/markdown', [ToolController::class, 'markdown'])->name('markdown');
    Route::get('/sql', [ToolController::class, 'sql'])->name('sql');
    Route::get('/base64', [ToolController::class, 'base64'])->name('base64');
    Route::get('/uuid', [ToolController::class, 'uuid'])->name('uuid');
    Route::get('/hash', [ToolController::class, 'hash'])->name('hash');
    Route::get('/url', [ToolController::class, 'url'])->name('url');
});

// SEO Routes
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly'],
        ['loc' => route('tools.csv'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.yaml'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.json'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.markdown'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.sql'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.base64'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.uuid'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.hash'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.url'), 'priority' => '0.8', 'changefreq' => 'monthly'],
    ];

    $content = view('sitemap', compact('urls'))->render();

    return response($content, 200)
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\n\nSitemap: " . route('sitemap');

    return response($content, 200)
        ->header('Content-Type', 'text/plain');
});
