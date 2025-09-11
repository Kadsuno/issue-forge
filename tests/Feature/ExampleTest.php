<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Root should redirect to dashboard first
        $this->get('/')
            ->assertRedirect(route('dashboard'));

        // Dashboard requires auth → guests are redirected to login
        $this->get('/dashboard')
            ->assertRedirect(route('login'));
    }
}
