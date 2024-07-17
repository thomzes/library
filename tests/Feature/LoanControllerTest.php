<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Laravel\Passport\Passport;
use Carbon\Carbon;



class LoanControllerTest extends TestCase
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

        // Create some loans with associated books
        $loan1 = Loan::factory()->create([
            'borrowed_at' => '2021-02-19',
            'borrower' => 'Prof. Garnet Boyer I',
        ]);
        $loan2 = Loan::factory()->create([
            'borrowed_at' => '2007-11-07',
            'borrower' => 'Prof. Gunner Turcotte III',
        ]);

        // Make a GET request to the index route
        $response = $this->getJson('/api/loan');

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'loans' => [
                '*' => [
                    'id',
                    'book' => [
                        'id',
                        'title',
                        'author',
                    ],
                    'borrowed_at',
                    'borrower',
                ],
            ],
            'message'
        ]);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'id' => $loan1->id,
            'book' => [
                'id' => $loan1->book->id,
                'title' => $loan1->book->title,
                'author' => $loan1->book->author,
            ],
            'borrowed_at' => '2021-02-19',
            'borrower' => 'Prof. Garnet Boyer I',
        ]);

        $response->assertJsonFragment([
            'id' => $loan2->id,
            'book' => [
                'id' => $loan2->book->id,
                'title' => $loan2->book->title,
                'author' => $loan2->book->author,
            ],
            'borrowed_at' => '2007-11-07',
            'borrower' => 'Prof. Gunner Turcotte III',
        ]);
    }

    public function testStore()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Acting as the created user using Passport
        Passport::actingAs($user);

        // Create a book to associate with the loan
        $book = Book::factory()->create();

        // Data for the loan
        $data = [
            'book_id' => $book->id,
            'borrower' => 'John Doe',
            'borrowed_at' => Carbon::now()->subDays(7)->toDateString(), // Example borrowed_at date
        ];

        // Make a POST request to store the loan
        $response = $this->postJson('/api/loan', $data);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'loan' => [
                'id',
                'book' => [
                    'id',
                    'title',
                    'author',
                ],
                'borrower',
                'borrowed_at',
                'returned_at',
            ],
            'message'
        ]);

        // Optionally, assert specific data in the response
        $response->assertJsonFragment([
            'borrower' => 'John Doe',
        ]);
    }

    public function testUpdate()
    {
        // Create a user for authentication with Passport
        Passport::actingAs(User::factory()->create());

        // Create a loan to update
        $loan = Loan::factory()->create([
            'borrower' => 'Initial Borrower',
            'borrowed_at' => '2023-01-01',
        ]);

        // New data to update the loan
        $newData = [
            'book_id' => Book::factory()->create()->id,
            'borrower' => 'Updated Borrower',
            'borrowed_at' => '2023-02-01',
            'returned_at' => '2023-03-01',
        ];

        // Make a PUT request to update the loan
        $response = $this->putJson("/api/loan/{$loan->id}", $newData);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'loan' => [
                'id',
                'book' => [
                    'id',
                    'title',
                    'author',
                ],
                'borrower',
                'borrowed_at',
                'returned_at',
            ],
            'message',
        ]);

        // Optionally, assert specific data in the response
        $response->assertJsonFragment([
            'borrower' => 'Updated Borrower',
        ]);
    }

    public function testDestroy()
    {
        // Create a test user
        $user = User::factory()->create();

        // Create a loan to delete
        $loan = Loan::factory()->create();

        // Acting as the authenticated user
        Passport::actingAs($user);

        // Send DELETE request to the endpoint
        $response = $this->deleteJson("/api/loan/{$loan->id}");

        // Assert the response status code
        $response->assertStatus(200);

        // Assert the response structure and message
        $response->assertJson([
            'message' => 'Successfully deleted loan data!'
        ]);
    }
}
