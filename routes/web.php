<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

Route::get('/', [GeminiController::class, 'showChat'])->name('home');
Route::post('/ask', [GeminiController::class, 'ask'])->name('ask');
Route::get('/archive', [GeminiController::class, 'archive'])->name('archive');