<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Api\QrScannerController;

Route::prefix('qr-scanner')->group(function () {
    Route::post('/scan', [QrScannerController::class, 'scan']);
    Route::post('/upload', [QrScannerController::class, 'uploadScan']);
});
