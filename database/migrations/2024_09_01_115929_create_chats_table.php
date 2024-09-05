<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id')->nullable()
                ->comment('Чат-ID сообщения');

            $table->unsignedBigInteger('message_id')->nullable()
                ->comment('ID сообщения');

            $table->text('message')->nullable()
                ->comment('Сообщение');

            $table->unsignedBigInteger('parent_message_id')->nullable()
                ->comment('ID родительского сообщения');

            $table->enum('source', ['admin', 'user'])->nullable()
                ->comment('Кто отправил сообщение');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
