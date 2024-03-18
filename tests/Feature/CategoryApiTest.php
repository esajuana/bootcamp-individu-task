<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Category;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_get_all_categories()
    {
        $response = $this->getJson('/api/categories'); // Update endpoint
        $response->assertStatus(200);
    }

    public function test_can_get_category_by_id()
    {
        $category = Category::factory()->create();
        $response = $this->getJson('/api/categories/' . $category->id); // Update endpoint
        $response->assertStatus(200);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();
        $response = $this->putJson('/api/categories/' . $category->id, [ // Change to putJson
            'name' => 'Updated Name',
        ]);
        $response->assertStatus(201);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id); // Update endpoint
        $response->assertStatus(200);
    }

    public function test_can_create_category()
    {
        $response = $this->postJson('/api/categories', [ // Update endpoint
            'name' => 'New Category',
        ]);
        $response->assertStatus(201);
    }


    public function test_can_restore_category()
    {
        $category = Category::factory()->create();
        $category->delete();
        $response = $this->putJson('/api/categories/trash/' . $category->id);
        $response->assertStatus(200);
    }
}
