<?php

declare(strict_types=1);

namespace App\Services;


use App\Models\TelegramMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Сервис коннекта с телеграм
 * Singleton
 */
class TelegramService
{
    private static TelegramService $resource;
    private string $message;
    private int $chatId;
    private int $messageId;

    private string $token;

    private function __construct()
    {
        $this->token = config('telegram.token');
        $this->setChatId();
        $this->setMessageId();
        $this->setMessage();
    }


    public function saveMessage(): Builder
    {
        return TelegramMessage::updateOrInsert([
            'chat_id'   => $this->chatId,
            'message_id'=> $this->messageId
        ], [
            'message'   => $this->message,
            'source'    => 'user',
            'created_at' => now(),
            'updated_at'=> now(),
        ]);
    }

    public static function getResource(): TelegramService
    {
        return self::$resource ?? new self();
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    private function setChatId(): void
    {
        $chatId = request()->has('message.chat.id') ? request()->input('message.chat.id') : 0;

        $chatId = (empty($chatId) && request()->has('edited_message.chat.id')) ?
            request()->input('edited_message.chat.id') :
            $chatId;

        $this->chatId = $chatId;
    }

    private function setMessageId(): void
    {
        $messageId = request()->has('message.message_id') ? request()->input('message.message_id') : 0;

        $messageId = (empty($messageId) && request()->has('edited_message.message_id')) ?
            request()->input('edited_message.message_id') :
            $messageId;

        $this->messageId = $messageId;
    }

    private function setMessage(): void
    {
        $message = request()->has('message.text') ? request()->input('message.text') : '';

        $message = (empty($message) && request()->has('edited_message.text')) ?
            request()->input('edited_message.text') :
            $message;

        $this->message = $message;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    private function __clone(){}
    private function __deserialize(){}
}
