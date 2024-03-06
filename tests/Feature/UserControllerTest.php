<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(User::factory()->create());
    }
    /** @test */
    public function testUserIndex()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function testUserStoreSuccess()
    {
        $userData = User::factory()->make()->toArray();
        $userData['password'] = '!Password123';

        $response = $this->post('/api/users', $userData);
        $response->assertStatus(201)
            ->assertJson(['message' => 'User Created Successfully']);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);
    }

    /** @test */
    public function testUserShowSuccess()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/users/{$user->id}");
        $response->assertStatus(200)
            ->assertJson(['data' => $user->toArray()]);
    }

    /** @test */
    public function testUserUpdateSuccess()
    {
        $user = User::factory()->create();
        $newData = [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
            'password' => '!Password328'
        ];

        $response = $this->put("/api/users/{$user->id}", $newData);
        $response->assertStatus(200)
            ->assertJson(['message' => 'User Updated Successfully']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'newemail@example.com'
        ]);
    }

    /** @test */
    public function testUserDestroySuccess()
    {
        $user = User::factory()->create();

        // Create a new user without admin role
        $unauthorizedUser = User::factory()->create();

        // Authenticate as the unauthorized user
        $this->actingAs($unauthorizedUser);

        $response = $this->delete("/api/users/{$user->id}");
        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);

        // Ensure the user is not deleted
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }


}
