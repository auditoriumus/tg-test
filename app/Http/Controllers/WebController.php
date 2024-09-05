<?php

namespace App\Http\Controllers;

use App\Models\TelegramMessage;
use App\Services\StoreAdminMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function main(): View
    {
        $conversations = TelegramMessage::with('replies')
            ->where('source', 'user')
            ->get()
            ->groupBy('chat_id');

        return view('welcome')->with([
            'conversations' => $conversations
        ]);
    }

    public function storeMessage(Request $request): JsonResponse
    {
        $request->validate([
            'admin_message' => 'required|string',
            'conversation'  => 'required|numeric|exists:telegram_messages,id'
        ]);

        //диалог, на который получаем ответ
        $conversation = TelegramMessage::with('parent')
            ->where('id', $request->input('conversation'))
            ->firstOrFail();

        app(StoreAdminMessage::class)->send($conversation, $request->input('admin_message'));

        return response()->json(['ok' => true], 201);
    }
}
