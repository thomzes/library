<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Book;
use App\Models\User;
use Laravel\Passport\Passport;
use Laravel\Sanctum\Sanctum;


class BookControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testIndex(){
        // Create a user (or use an existing one)
        $user = User::factory()->create();

        // Create some sample categories
        $fiction = Category::factory()->create(['name' => 'Fiction']);
        $nonFiction = Category::factory()->create(['name' => 'Non-Fiction']);

        // Create some sample books and associate them with categories
        $book1 = Book::factory()->create(['title' => 'Book 1', 'author' => 'Author 1']);
        $book1->categories()->attach($fiction->id);

        $book2 = Book::factory()->create(['title' => 'Book 2', 'author' => 'Author 2']);
        $book2->categories()->attach($nonFiction->id);

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Make a GET request to the index route
        $response = $this->getJson('/api/book');

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'Books' => [
                '*' => [
                    'title',
                    'author',
                    'categories' => [
                        '*' => [
                            'name',
                        ]
                    ]
                ]
            ],
            'message'
        ]);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'title' => 'Book 1',
            'author' => 'Author 1',
            'name' => 'Fiction',
        ]);

        $response->assertJsonFragment([
            'title' => 'Book 2',
            'author' => 'Author 2',
            'name' => 'Non-Fiction',
        ]);
    }

    public function testStore()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Create some sample categories
        $fiction = Category::factory()->create(['name' => 'Fiction']);
        $nonFiction = Category::factory()->create(['name' => 'Non-Fiction']);

        // Prepare the request data
        $data = [
            'title' => 'New Book',
            'author' => 'New Author',
            'category_ids' => [$fiction->id, $nonFiction->id]
        ];

        // Make a POST request to the store route
        $response = $this->postJson('/api/book', $data);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'book' => [
                'title',
                'author',
                'categories' => [
                    '*' => [
                        'name',
                    ]
                ]
            ],
            'message'
        ]);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'title' => 'New Book',
            'author' => 'New Author',
        ]);

        $response->assertJsonFragment([
            'name' => 'Fiction',
        ]);

        $response->assertJsonFragment([
            'name' => 'Non-Fiction',
        ]);

        // Assert the book is stored in the database
        $this->assertDatabaseHas('books', [
            'title' => 'New Book',
            'author' => 'New Author',
        ]);

        // Assert the categories are attached to the book
        $book = Book::where('title', 'New Book')->first();
        $this->assertTrue($book->categories->contains($fiction));
        $this->assertTrue($book->categories->contains($nonFiction));
    }

    public function testUpdate()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Create some sample categories
        $fiction = Category::factory()->create(['name' => 'Fiction']);
        $nonFiction = Category::factory()->create(['name' => 'Non-Fiction']);

        // Create a sample book and associate it with a category
        $book = Book::factory()->create(['title' => 'Old Title', 'author' => 'Old Author']);
        $book->categories()->attach($fiction->id);

        // Prepare the request data
        $data = [
            'title' => 'New Title',
            'author' => 'New Author',
            'category_ids' => [$fiction->id, $nonFiction->id]
        ];

        // Make a PUT request to the update route
        $response = $this->putJson("/api/book/{$book->id}", $data);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'book' => [
                'title',
                'author',
                'categories' => [
                    '*' => [
                        'name',
                    ]
                ]
            ],
            'message'
        ]);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        $response->assertJsonFragment([
            'name' => 'Fiction',
        ]);

        $response->assertJsonFragment([
            'name' => 'Non-Fiction',
        ]);

        // Assert the book is updated in the database
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        // Assert the categories are updated for the book
        $book->refresh(); // Refresh the model instance
        $this->assertTrue($book->categories->contains($fiction));
        $this->assertTrue($book->categories->contains($nonFiction));
    }

    public function testDestroy()
    {
        // Create a user
        $user = User::factory()->create();

        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Create some sample categories
        $fiction = Category::factory()->create(['name' => 'Fiction']);
        $nonFiction = Category::factory()->create(['name' => 'Non-Fiction']);

        // Create a sample book and associate it with categories
        $book = Book::factory()->create(['title' => 'Book to Delete', 'author' => 'Author']);
        $book->categories()->attach([$fiction->id, $nonFiction->id]);

        // Make a DELETE request to the destroy route
        $response = $this->deleteJson("/api/book/{$book->id}");

        // Assert the response status
        $response->assertStatus(200);

        // Assert the response contains the correct message
        $response->assertJson([
            'message' => 'Book deleted successfully.',
        ]);

        // Assert the book is deleted from the database
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);

        // Assert the categories are still in the database (they should not be affected)
        $this->assertDatabaseHas('categories', [
            'name' => 'Fiction',
        ]);
        
        $this->assertDatabaseHas('categories', [
            'name' => 'Non-Fiction',
        ]);
    }

    
}
