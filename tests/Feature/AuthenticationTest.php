<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function createPersonalClient()
    {
        Passport::$hashesClientSecrets = false;

        $this->artisan(
            'passport:client',
            ['--name' => config('app.name'), '--personal' => null]
        );

        // Use the query builder instead of the model to retrieve the client secret
        return DB::table('oauth_clients')
            ->where('personal_access_client', '=', true)
            ->first();
    }

    /**
     * Test user registration with valid data.
     *
     * @return void
     */
    public function testUserRegistrationSuccess()
    {
        $this->createPersonalClient();
        
        $userData = [
            'name' => 'Alan Abiodun',
            'email' => 'alanson@gmail.com',
            'password' => '!Password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
                 ->assertJson([
                    'message' => 'Registration Successfully',
                    'token' => true
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'alanson@gmail.com'
        ]);
    }

    /**
     * Test user registration with invalid data.
     *
     * @return void
     */
    public function testUserRegistrationFailure()
    {
        $this->createPersonalClient();

        // Missing name field
        $userData = [
            'email' => 'alanson@gmail.com',
            'password' => '!Password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);

        // Duplicate email
        $existingUser = User::factory()->create([
            'email' => 'alanson@gmail.com',
        ]);

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test user login with valid credentials.
     *
     * @return void
     */
    public function testUserLoginSuccess()
    {
        $this->createPersonalClient();

        $user = User::factory()->create([
            'email' => 'alanson@gmail.com',
            'password' => Hash::make('!Password123'),
        ]);

        $loginData = [
            'email' => 'alanson@gmail.com',
            'password' => '!Password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJson([
                    'message' => 'Login Successfully',
                    'token' => true
                 ]);
    }

    /**
     * Test user login with invalid credentials.
     *
     * @return void
     */
    public function testUserLoginFailure()
    {
        $this->createPersonalClient();

        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $invalidLoginData = [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $invalidLoginData);

        $response->assertStatus(401)
                 ->assertJson([
                    'message' => 'Invalid email or password'
                 ]);
    }
}
