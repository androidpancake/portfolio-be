<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
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
        $data = Categories::with('projects')->get();
        return apiResponseClass::sendResponse($data, '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // var_dump($data);

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        if($validatedData)
        {
            $db = Categories::create($validatedData);
            return apiResponseClass::sendResponse($db, 'Category created successfully', 201);
        }

        // // var_dump($data);
        // try {
        //     //code...
        //     $validatedData = $request->validate([
        //         'name' => 'required',
        //     ]);

        //     $db = Categories::create($validatedData);
        //     return apiResponseClass::sendResponse($db, 'Category created successfully', 201);
        // } catch (\Exception $th) {
        //     //throw $th;
        //     return apiResponseClass::rollback($th, 'Category creation failed', 500);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
