<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_if_user_can_register()
    {
        $this->withoutExceptionHandling();
        $user = make(\App\User::class);

        $payload = $user->toArray();
        $payload['password'] = 'password';

        $response = $this->post('/api/register', $payload, createHeaders());
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_if_user_can_login()
    {
        $user = create(\App\User::class);

        $response = $this->post('/api/login', ['email' => $user->email, 'password' => 'password'], createHeaders());

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_if_user_can_see_their_profile()
    {
        $user = create(\App\User::class);

        $response = $this->get('/api/user', createHeaders($user));

        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => $user->email]);
    }
}
