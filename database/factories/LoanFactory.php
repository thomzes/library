<?php

namespace Database\Factories;
use App\Models\Loan;
use App\Models\Book;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Loan::class;

    public function definition()
    {
        return [
            'book_id' => Book::factory(),
            'borrower' => $this->faker->name,
            'borrowed_at' => $this->faker->date,
            'returned_at' => null,
        ];
    }
}
