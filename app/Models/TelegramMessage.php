<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'telegram_messages';

    protected $fillable = [
        'chat_id',
        'message',
        'message_id',
        'parent_message_id',
        'source',
        'created_at',
        'updated_at'
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(TelegramMessage::class, 'parent_message_id','message_id');
    }

    //родительское сообщение, на которое был ответ
    public function parent(): HasOne
    {
        return $this->hasOne(TelegramMessage::class, 'message_id','parent_message_id');
    }
}
