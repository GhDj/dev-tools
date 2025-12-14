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
    Route::get('/code-editor', [ToolController::class, 'codeEditor'])->name('code-editor');
    Route::get('/regex', [ToolController::class, 'regex'])->name('regex');
    Route::get('/base-converter', [ToolController::class, 'baseConverter'])->name('base-converter');
    Route::get('/slug-generator', [ToolController::class, 'slugGenerator'])->name('slug-generator');
    Route::get('/color-picker', [ToolController::class, 'colorPicker'])->name('color-picker');
    Route::get('/qr-code', [ToolController::class, 'qrCode'])->name('qr-code');
    Route::get('/html-entity', [ToolController::class, 'htmlEntity'])->name('html-entity');
    Route::get('/text-case', [ToolController::class, 'textCase'])->name('text-case');
    Route::get('/password', [ToolController::class, 'password'])->name('password');
    Route::get('/lorem', [ToolController::class, 'lorem'])->name('lorem');
    Route::get('/cron', [ToolController::class, 'cron'])->name('cron');
    Route::get('/jwt', [ToolController::class, 'jwt'])->name('jwt');
});

// Static Pages
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/about', 'about')->name('about');

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
        ['loc' => route('tools.code-editor'), 'priority' => '0.9', 'changefreq' => 'monthly'],
        ['loc' => route('tools.regex'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.base-converter'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.slug-generator'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.color-picker'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.qr-code'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.html-entity'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.text-case'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.password'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.lorem'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.cron'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('tools.jwt'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('about'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ['loc' => route('privacy'), 'priority' => '0.3', 'changefreq' => 'yearly'],
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
