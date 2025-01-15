<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\ProjectResource;
use App\Models\Projects;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Projects::with('Categories')->get();
        return apiResponseClass::sendResponse(ProjectResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'required',
            'image' => 'required',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        try {
            //code...
            $db = Projects::create($validatedData);
            return apiResponseClass::sendResponse($db, 'Project created successfully', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return apiResponseClass::rollback($th);
        }
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
