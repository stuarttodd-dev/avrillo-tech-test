<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\QuotesController::class)->group(function () {
    Route::get('/', 'get')->name('quotes.get');
    Route::get('/refresh', 'refresh')->name('quotes.refresh');
});
