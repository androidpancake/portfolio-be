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
        $data = Projects::with('categories', 'skills')->get();
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required',
            'end_date' => 'required',
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
        ]);

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $image->storeAs('images', $name, 'public');
                $validatedData['image'] = $destinationPath;
            }

            $db = Projects::create($validatedData);
            $db->skills()->attach($request->skills);
            return apiResponseClass::sendResponse($db, 'Project created successfully', 201);
        } catch (\Throwable $th) {
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
