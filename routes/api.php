<?php

use App\Http\Controllers\Api\Base64Controller;
use App\Http\Controllers\Api\CsvController;
use App\Http\Controllers\Api\MarkdownController;
use App\Http\Controllers\Api\SqlController;
use App\Http\Controllers\Api\YamlController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/csv/convert', [CsvController::class, 'convert']);
    Route::post('/yaml/convert', [YamlController::class, 'convert']);
    Route::post('/markdown/convert', [MarkdownController::class, 'convert']);
    Route::post('/sql/format', [SqlController::class, 'format']);
    Route::post('/base64/encode', [Base64Controller::class, 'encode']);
    Route::post('/base64/decode', [Base64Controller::class, 'decode']);
    Route::post('/base64/encode-file', [Base64Controller::class, 'encodeFile']);
});
