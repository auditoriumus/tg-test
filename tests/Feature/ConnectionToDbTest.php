<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConnectionToDbTest extends TestCase
{
    public function testTableExists(): void
    {
        $this->assertDatabaseHas('telegram_messages');
    }
}
