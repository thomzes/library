<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $loans = Loan::with('book')->get();
    
            return response(['loans' => LoanResource::collection($loans), 'message' => "Successfully shows all data of Book Loans!"], 200);
        } catch (QueryException $e) {
            return response(['error' => 'Failed to retrieve category data.', 'message' => 'Error occurred while processing request.'], 500);
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
                'book_id' => 'required|exists:books,id',
                'borrower' => 'required',
                'borrowed_at' => 'required|date'
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
            $loan = Loan::create($request->all());
    
            return response(['loan' => new LoanResource($loan), 'message' => 'Successfully added load data!'], 200);
        } catch (ValidationException $e) {
            return response(['error' => $e->errors(), 'message' => 'Validation Error!'], 422);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        try {
            $data = $request->all();
    
            $validator = Validator::make($data, [
                'book_id' => 'required|exists:books,id',
                'borrower' => 'required',
                'borrowed_at' => 'required|date',
                'returned_at' => 'nullable|date'
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $loan->update($request->all());
    
            return response(['loan' => new LoanResource($loan), 'message' => 'Successfully updated loan data!'], 200);
        } catch (ValidationException $e) {
            return response(['error' => $e->errors(), 'message' => 'Validation Error!'], 422);
        } catch (ModelNotFoundException $e) {
            return response(['error' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response(['error' => 'An error occurred while processing the request.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        try {
            // Delete the loan
            $loan->delete();
            
            return response(['message' => 'Successfully deleted loan data!'], 200);
        } catch (ModelNotFoundException $e) {
            return response(['error' => 'Loan not found.'], 404);
        } catch (\Exception $e) {
            return response(['error' => 'An error occurred while processing the request.'], 500);
        }
    }

    public function borrowedBooks()
    {
        return Loan::with('book')->whereNull('returned_at')->get();
    }

    public function returnBook(Request $request, Loan $loan)
    {
        try {
            // dd($loan);
            $request->validate(['returned_at' => 'required|date']);

            $loan->update(['returned_at' => $request->returned_at]);

            return response()->json(['loan' => $loan, 'message' => 'Book return date successfully updated!'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Validation Error!'], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'An error occurred while updating the return date.'], 500);
        }
    }
}
