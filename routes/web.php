<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, 'main']);
Route::post('add_message', [WebController::class, 'storeMessage']);
