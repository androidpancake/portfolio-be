<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\SkillsResource;
use App\Models\Categories;
use App\Models\Skills;
use App\SkillLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Skills::all();
        return apiResponseClass::sendResponse(SkillsResource::collection($data), 'Successfully retrieved skills data', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:skills,name',
            'level' => ['required', new Enum(SkillLevel::class)],
            'images' => 'required'
        ]);

        // if($request->hasFile('images'))
        // {
        //     $image = 
        // }

        if ($validatedData) {
            try {
                $db = Skills::create($validatedData);
                return apiResponseClass::sendResponse($db, 'Skill created successfully', 201);
            } catch (\Throwable $th) {
                return apiResponseClass::rollback($th->getMessage(), 'Error creating skill');
            }
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
    public function update(Request $request, Skills $skills)
    {
        $skillData = Skills::findOrFail($skills->id);

        $validatedData = $request->validate([
            'name' => 'nullable|unique:skill,name',
            'level' => 'required|in:beginner,intermediate,expert'
        ]);

        // dd($validatedData);

        try {
            //code...
            if ($validatedData) {
                $db = $skillData->where('id', $skillData->id)->update($validatedData);
                return apiResponseClass::sendResponse($db, 'Skills updated successfully', 200);
            } else {
                return apiResponseClass::sendError($validatedData, 'Failed to update skill', 400);
            }
        } catch (\Throwable $th) {
            return apiResponseClass::sendError($th->getMessage(), 'Error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skills $skills)
    {
        $skills->delete();

        return apiResponseClass::sendResponse($skills->name, 'Success Delete Skill' . $skills->name, 201);
    }
}
