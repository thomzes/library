<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Address;
use App\Models\Book;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // $books = Book::join('category', 'book.category_id', '=', 'category.id')
            // ->select('book.title', 'book.author', 'category.name')
            // ->distinct('book.title', 'book.author', 'category.name')
            // ->get();

            $books = Book::with('categories')->get();
    
            return response(['Books' => BookResource::collection($books), 'message' => "Successfully shows all data of Books!"], 200);
        } catch (QueryException $e) {
            return response(['message' => "Failed to retrieve Books data.", 'message' => 'Error occurred while processing request.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
        
            $validator = Validator::make($data, [
                'title' => 'required',
                'author' => 'required',
                'category_ids' => 'required|array'
            ]);
        
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        
            $book = Book::create($request->only(['title', 'author']));
            $book->categories()->attach($request->category_ids);

            // Load categories relationship
            $book->load('categories');
        
            return response(['book' => new BookResource($book), 'message' => 'Successfully added book data!'], 200);
        } catch (ValidationException $e) {
            return response(['error' => $e->errors(), 'message' => 'Validation Error!'], 422);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        try {
            // dd($book->id);
            $book->load('categories');

            // Check if $book is null (not found)
            if (!$book) {
                throw new ModelNotFoundException("Book not found.");
            }

            return response(['Book' => new BookResource($book), 'message' => 'Successfully viewed customer data!'], 200);
        } catch (QueryException $e) {
            return response(['error' => 'Failed to retrieve customer address data.', 'message' => 'Error occurred while processing request.'], 500);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        try {
        $data = $request->all();
        
        $validator = Validator::make($data, [
            'title' => 'required',
            'author' => 'required',
            'category_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $book->update($request->only(['title', 'author']));
        $book->categories()->sync($request->category_ids);

        $book->load('categories');

        return response(['book' => new BookResource($book), 'message' => 'Successfully update book data!'], 200);
        } catch (ValidationException $e) {
            return response(['error' => $e->errors(), 'message' => 'Validation Error!'], 422);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        try {
            $book->categories()->detach(); // Detach relationships
            $book->delete(); // Delete the book
    
            return response()->json(['message' => 'Book deleted successfully.'], 200); // Success message with 204 No Content
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete the book.'], 500); // Handle any exceptions with a 500 Internal Server Error
        }
    }
}
