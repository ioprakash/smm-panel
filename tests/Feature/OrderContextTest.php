<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderContextTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_order_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('orders.new'));
        $response->assertStatus(200);
    }

    public function test_user_can_place_order_with_sufficient_balance()
    {
        $user = User::factory()->create(['balance' => 1000]);
        $category = Category::create(['name' => 'Test Cat', 'slug' => 'test']);
        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Test Service',
            'price' => 100, // NPR per 1000
            'min_quantity' => 10,
            'max_quantity' => 1000,
        ]);

        $response = $this->actingAs($user)->post(route('orders.store'), [
            'service_id' => $service->id,
            'link' => 'http://example.com',
            'quantity' => 100, // Cost = (100/1000)*100 = 10 NPR
        ]);

        $response->assertRedirect(route('orders.history'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'charge' => 10,
            'status' => 'pending'
        ]);
        
        $this->assertEquals(990, $user->fresh()->balance);
    }

    public function test_user_cannot_place_order_with_insufficient_balance()
    {
        $user = User::factory()->create(['balance' => 5]); // Low balance
        $category = Category::create(['name' => 'Test Cat', 'slug' => 'test']);
        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Test Service',
            'price' => 100, 
            'min_quantity' => 10,
            'max_quantity' => 1000,
        ]);

        $response = $this->actingAs($user)->post(route('orders.store'), [
            'service_id' => $service->id,
            'link' => 'http://example.com',
            'quantity' => 100, // Cost 10
        ]);

        $response->assertSessionHasErrors('balance');
        $this->assertEquals(5, $user->fresh()->balance);
    }
}
