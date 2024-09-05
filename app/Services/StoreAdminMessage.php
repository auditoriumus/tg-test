<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AdminMessage;
use App\Models\TelegramMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Throwable;

class StoreAdminMessage
{
    /**
     * @throws Throwable
     */
    public function send(Model $conversation, string $newMessage): Model
    {
        $response = $this->exec($conversation, $newMessage);
        try {
            $messageId = $response['messageId'];
            $chatId = $response['chatId'];
        } catch (Throwable $e) {
            throw $e;
        }

        $parentId = empty($conversation->parent) ?
            $conversation->message_id :
            $conversation->parent->message_id;

        return TelegramMessage::create([
            'message_id'          => $messageId,
            'chat_id'             => $chatId,
            'message'             => $newMessage,
            'parent_message_id'   => $parentId,
            'source'              => 'admin'
        ]);
    }

    protected function exec(Model $conversation, string $newMessage): array
    {
        $tg = TelegramService::getResource();
        $token = $tg->getToken();

        $response = Http::post("https://api.telegram.org/bot$token/sendMessage",[
            'chat_id'           => $conversation->chat_id,
            'text'              => $newMessage,
            'reply_parameters'  => [
                'message_id' => $conversation->message_id
            ]
        ]);

        $result = [];
        $resArray = json_decode($response->body(), true);
        if ($response->getStatusCode() === 200 && $resArray['ok']) {
            $result['messageId'] = $resArray['result']['message_id'];
            $result['chatId'] = $resArray['result']['chat']['id'];
        }

        return $result;
    }
}
