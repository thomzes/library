<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;



class CategoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testIndex()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Create some sample categories
        $category1 = Category::factory()->create(['name' => 'Fiction']);
        $category2 = Category::factory()->create(['name' => 'Non-Fiction']);

        // Create some sample books and associate them with categories
        $book1 = Book::factory()->create(['title' => 'Book 1', 'author' => 'Author 1']);
        $book2 = Book::factory()->create(['title' => 'Book 2', 'author' => 'Author 2']);

        $category1->books()->attach($book1->id);
        $category2->books()->attach($book2->id);

        // Make a GET request to the index route
        $response = $this->getJson('/api/category');

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'category' => [
                '*' => [
                    'id',
                    'name',
                    'books' => [
                        '*' => [
                            'title',
                            'author',
                        ]
                    ]
                ]
            ],
            'message'
        ]);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'name' => 'Fiction',
            'title' => 'Book 1',
            'author' => 'Author 1',
        ]);

        $response->assertJsonFragment([
            'name' => 'Non-Fiction',
            'title' => 'Book 2',
            'author' => 'Author 2',
        ]);
    }

    public function testStore()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Define the data to be sent with the POST request
        $data = [
            'name' => 'New Category',
        ];

        // Make a POST request to the store route
        $response = $this->postJson('/api/category', $data);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'category' => [
                'id',
                'name',
            ],
            'message'
        ]);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'name' => 'New Category',
        ]);

        // Assert the category was created in the database
        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
        ]);
    }

    /**
     * Test validation error for the store method of the CategoryController.
     *
     * @return void
     */
    public function testStoreValidationError()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Define invalid data (missing 'name')
        $data = [];

        // Make a POST request to the store route
        $response = $this->postJson('/api/category', $data);

        // Assert the response status
        $response->assertStatus(422);

        // Assert the structure of the error response
        $response->assertJsonStructure([
            'error',
            'message'
        ]);

        // Assert the validation error message
        $response->assertJsonFragment([
            'message' => 'Validation Error!',
        ]);
    }

    public function testShow()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category
        $category = Category::factory()->create(['name' => 'Test Category']);

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Make a GET request to the show route
        $response = $this->getJson('/api/category/' . $category->id);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'category' => [
                'id',
                'name',
            ],
            'message'
        ]);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'name' => 'Test Category',
        ]);
    }

    /**
     * Test not found error for the show method of the CategoryController.
     *
     * @return void
     */
    public function testShowNotFound()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Make a GET request to a non-existent category ID
        $response = $this->getJson('/api/category/999');

        // Assert the response status
        $response->assertStatus(404);

        // Assert the structure of the error response
        $response->assertJsonStructure([
            'error',
        ]);

        // Assert the not found error message
        $response->assertJsonFragment([
            'error' => 'Category not found.',
        ]);
    }

    public function testDestroy()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category
        $category = Category::factory()->create(['name' => 'Test Category']);

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Make a DELETE request to the destroy route
        $response = $this->deleteJson('/api/category/' . $category->id);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'message'
        ]);

        // Assert the response message
        $response->assertJsonFragment([
            'message' => 'Successfully deleted category data!',
        ]);

        // Assert the category was deleted from the database
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * Test not found error for the destroy method of the CategoryController.
     *
     * @return void
     */
    public function testDestroyNotFound()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Make a DELETE request to a non-existent category ID
        $response = $this->deleteJson('/api/category/999');

        // Assert the response status
        $response->assertStatus(404);

        // Assert the structure of the error response
        $response->assertJsonStructure([
            'error',
        ]);

        // Assert the not found error message
        $response->assertJsonFragment([
            'error' => 'Category not found.',
        ]);
    }






}
