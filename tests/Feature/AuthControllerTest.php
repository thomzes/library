<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    // use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install', ['--no-interaction' => true,]);
    }
    
    public function test_register()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'access_token'
            ]);
    }

    public function test_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ],
                'access_token'
            ]);
    }



}