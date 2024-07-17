<?php

namespace Tests\Feature;

use Tests\TestCase;

class DataTest extends TestCase
{
    public function testGetUsers()
    {
        $response = $this->get('/api/v1/data');
        $response->assertStatus(200);
        // Add more assertions to validate response
    }
}
