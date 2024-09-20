<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_user(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('profile.jpg');

        $response = $this->postJson('/api/register', [
            'name' => 'Vicko Amelino Syahputra',
            'email' => 'viereonc@gmail.com',
            'password' => 'testing123456',
            'phone_number' => '081234567890',
            'role' => 'user',
            'photo' => $photo,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['access_token', 'token_type']);

        $this->assertDatabaseHas('users', ['email' => 'viereonc@gmail.com']);
    }

    /** @test */
    public function login_user(): void
    {
        User::factory()->create([
            'email' => 'viereonc@gmail.com',
            'password' => 'testing123456',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'viereonc@gmail.com',
            'password' => 'testing123456',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type']);
    }

    /** @test */
    public function logout_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Successfully logged out.']);
    }

     /** @test */
     public function update_profile_user()
     {
         $user = User::factory()->create();
         $this->actingAs($user, 'sanctum');

         $response = $this->putJson('/api/profile', [
             'name' => 'Updated Name',
             'phone_number' => '08123459690',
             'email' => $user->email,
         ]);

         $response->assertStatus(200)
                  ->assertJson(['message' => 'Profile updated successfully.']);

         $this->assertDatabaseHas('users', ['name' => 'Updated Name']);
     }
}
