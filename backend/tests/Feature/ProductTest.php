<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_read_products_without_authentication()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $data = $response->json();
        $products = $data['data'] ?? []; // Get the 'data' key if pagination is used

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data'); // Check the 'data' key if pagination is used
    }

    /** @test */
    public function it_can_read_product_details_without_authentication()
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_product_as_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $token = $admin->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/products', [
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 100.00,
            'category' => 'Category',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'name' => 'Product Name',
                     'description' => 'Product Description',
                     'price' => 100.00,
                     'category' => 'Category',
                 ]);
    }

    /** @test */
    public function it_cannot_create_a_product_as_user()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/products', [
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 100.00,
            'category' => 'Category',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_update_a_product_as_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $token = $admin->createToken('TestToken')->plainTextToken;

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson('/api/products/' . $product->id, [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'price' => 200.00,
            'category' => 'Updated Category',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'name' => 'Updated Name',
                     'description' => 'Updated Description',
                     'price' => 200.00,
                     'category' => 'Updated Category',
                 ]);
    }

    /** @test */
    public function it_cannot_update_a_product_as_user()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $token = $user->createToken('TestToken')->plainTextToken;

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson('/api/products/' . $product->id, [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'price' => 200.00,
            'category' => 'Updated Category',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_a_product_as_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $token = $admin->createToken('TestToken')->plainTextToken;

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_cannot_delete_a_product_as_user()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $token = $user->createToken('TestToken')->plainTextToken;

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(403);
    }
}
