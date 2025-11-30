<?php

use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ToolController::class, 'index'])->name('home');

Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/csv', [ToolController::class, 'csv'])->name('csv');
    Route::get('/yaml', [ToolController::class, 'yaml'])->name('yaml');
    Route::get('/markdown', [ToolController::class, 'markdown'])->name('markdown');
    Route::get('/sql', [ToolController::class, 'sql'])->name('sql');
    Route::get('/base64', [ToolController::class, 'base64'])->name('base64');
});
