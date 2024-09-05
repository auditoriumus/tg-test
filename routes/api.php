<?php

use App\Http\api\v1\StartController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1'
], function () {
    Route::match(['get', 'post'], '/', [StartController::class, 'start']);
});
