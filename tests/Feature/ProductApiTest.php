<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_can_get_all_products()
    {
        $response = $this->getJson('/api/products'); // Update endpoint
        $response->assertStatus(200);
    }

    public function test_can_get_product_by_id()
    {
        $product = Product::factory()->create();
        $response = $this->getJson('/api/products/' . $product->id); // Update endpoint
        $response->assertStatus(200);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();
        $response = $this->putJson('/api/products/' . $product->id, [ // Change to putJson
            'name' => 'Updated Name',
            'price' => 100,
            'stock' => 10,
            'category_id' => $product->category_id,
        ]);
        $response->assertStatus(201);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson('/api/products/' . $product->id); // Update endpoint
        $response->assertStatus(200);
    }

    public function test_can_create_product()
    {
        $response = $this->postJson('/api/products', [ // Update endpoint
            'name' => 'New Product',
            'price' => 50,
            'stock' => 20,
            'category_id' => 1
        ]);
    
        $response->assertStatus(201);
    }


    public function test_can_restore_product()
    {
        $product = Product::factory()->create();
        $product->delete();
        $response = $this->putJson('/api/product/trash/' . $product->id);
        $response->assertStatus(200);
    }
}
