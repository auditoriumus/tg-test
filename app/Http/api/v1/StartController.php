<?php

namespace App\Http\api\v1;

use App\Http\Controllers\Controller;
use App\Services\TelegramService;

class StartController extends Controller
{
    public function start()
    {
        $tgResource = TelegramService::getResource();
        $tgResource->saveMessage();
        return response()->json(['ok' => true]);
    }
}
