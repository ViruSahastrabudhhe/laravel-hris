<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Api\QrScannerController;
use App\Http\Controllers\Chatbot\ChatbotController;

//Route::middleware(['auth', 'verified'])->group(function() {
//    Route::post('/chatbot/chat', [ChatbotController::class, 'chat']);
//});

Route::prefix('qr-scanner')->group(function () {
    Route::post('/scan', [QrScannerController::class, 'scan']);
    Route::post('/upload', [QrScannerController::class, 'uploadScan']);
});
