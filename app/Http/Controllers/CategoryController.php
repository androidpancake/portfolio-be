<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\CategoryResource;
use App\Models\Categories;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Categories::all();
        return apiResponseClass::sendResponse(CategoryResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        // dd($validatedData);

        if ($validatedData) {
            $db = Categories::create($validatedData);
            return apiResponseClass::sendResponse($db, 'Category created successfully', 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categories $categories)
    {
        $categoryData = Categories::findOrFail($categories);

        return apiResponseClass::sendResponse($categoryData, 'Success', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categories $categories)
    {
        $categoryData = Categories::findOrFail($categories->id);

        // dd($categoryData);

        $validatedData = $request->validate([
            'name' => 'nullable|unique:categories,name'
        ]);

        // dd($validatedData);

        try {
            //code...
            if ($validatedData) {
                $db = $categoryData->where('id', $categoryData->id)->update($validatedData);
                return apiResponseClass::sendResponse($db, 'Category updated successfully', 200);
            } else {
                return apiResponseClass::sendError($validatedData, 'Failed to update category', 400);
            }
        } catch (\Throwable $th) {
            return apiResponseClass::sendError($th->getMessage(), 'Error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categories $categories)
    {
        $categoryData = Categories::findOrFail($categories->id);
        $categoryData->delete();

        $success = 'Success Delete Data';

        return apiResponseClass::sendResponse($categoryData->name, 'Success Delete Data', 201);
    }
}
