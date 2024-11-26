<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Places\RestaurantFinder;

class RestaurantFinderTest extends TestCase
{
    public function test_restaurant_page_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/restaurants');

        $response->assertStatus(200);
    }

    public function test_restaurant_component_can_load_restaurants()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(RestaurantFinder::class)
            ->assertSuccessful()
            ->assertViewIs('livewire.places.restaurant-finder')
            ->assertSee('Zagreb Restaurants');
    }
}
