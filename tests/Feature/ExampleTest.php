<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test that root route redirects guest users appropriately.
     */
    public function test_the_application_redirects_unauthenticated_user(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
