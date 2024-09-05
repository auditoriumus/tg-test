<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddMessageFailedTest extends TestCase
{
    public function testAddFailedMessage(): void
    {
        $response = $this->json('POST', '/add_message');
        $response->assertStatus(422);
    }
}
