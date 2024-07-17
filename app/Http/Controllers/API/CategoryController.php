<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = Category::with('books')->get();
    
            return response(['category' => CategoryResource::collection($categories), 'message' => "Successfully shows all data of Category!"], 200);
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
                'name' => 'required',
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
            $category = Category::create($data);
    
            return response(['category' => new CategoryResource($category), 'message' => 'Successfully added category data!'], 200);
        } catch (ValidationException $e) {
            return response(['error' => $e->errors(), 'message' => 'Validation Error!'], 422);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        try {
            $perCategory = Category::findOrFail($category->id);
            return response(['category' => new CategoryResource($perCategory), 'message' => 'Successfully viewed category data!'], 200);
        } catch (ModelNotFoundException $e) {
            return response(['error' => 'Category not found.'], 404);
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
    public function update(Request $request, Category $category)
    {
        try {
            $validatedData = $request->validate(['name' => 'required']);
    
            $category->update($validatedData);
    
            return response(['category' => new CategoryResource($category), 'message' => 'Successfully updated category data!'], 200);
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            // Detach all books associated with the category
            $category->books()->detach();
            
            // Delete the category
            $category->delete();
            
            return response(['message' => 'Successfully deleted category data!'], 200);
        } catch (ModelNotFoundException $e) {
            return response(['error' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response(['error' => 'An error occurred while processing the request.'], 500);
        }
    }
}
