<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'farmer',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_seller_registers_as_pending(): void
    {
        $response = $this->post('/register', [
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'seller',
            'phone' => '1234567890',
            'address' => 'Test Warehouse Address',
        ]);

        $this->assertAuthenticated();
        $user = \Illuminate\Support\Facades\Auth::user();
        $this->assertEquals('seller', $user->role);
        $this->assertEquals('pending', $user->status);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_farmer_can_apply_to_become_seller(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'farmer',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->post('/dashboard/become-seller', [
            'phone' => '0987654321',
            'store_name' => 'Golden Farms Store',
            'address' => 'New Dispatch Center 123',
        ]);

        $user->refresh();
        $this->assertEquals('seller', $user->role);
        $this->assertEquals('pending', $user->status);
        $this->assertEquals('Golden Farms Store', $user->name);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_unapproved_seller_is_redirected_to_status_view(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'seller',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertViewIs('dashboards.seller-status');
    }

    public function test_unapproved_seller_can_revert_to_farmer(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'seller',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->post('/dashboard/revert-farmer');

        $user->refresh();
        $this->assertEquals('farmer', $user->role);
        $this->assertEquals('approved', $user->status);
        $response->assertRedirect(route('dashboard'));
    }
}
