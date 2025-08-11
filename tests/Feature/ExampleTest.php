<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
// tests/Feature/ExampleTest.php

  public function test_the_application_returns_a_successful_response(): void
  {
    $response = $this->get('/');

    // Change this line:
    // $response->assertStatus(200);

    // To one of these:
    $response->assertStatus(302); // Asserts it was a redirect
    // OR, even better:
    $response->assertRedirect('/login'); // Asserts it redirected to the login page
  }
}
