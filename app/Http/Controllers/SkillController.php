<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\SkillsResource;
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
        $data = Skills::with('projects')->get();
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
        ]);

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
