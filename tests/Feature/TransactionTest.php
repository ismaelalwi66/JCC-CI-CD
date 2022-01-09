<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    public function test_route_transaction()
    {
        $response = $this->getJson('api/transaction/history/{id}');

        $response->assertStatus(200);
    }
}
