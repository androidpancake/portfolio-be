<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\EducationResource;
use App\Models\Educations;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Educations::all();
        return apiResponseClass::sendResponse($data, 'Success retrieve education data', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'location' => 'required',
            'gpa' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'major' => 'required',
            'faculty' => 'required'
        ]);

        $db = Educations::create($validatedData);
        return apiResponseClass::sendResponse($db, 'Education created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $educationData = Educations::find($id)->get();

        return apiResponseClass::sendResponse($educationData, 'Success get detail', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $educationData = Educations::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required',
            'location' => 'required',
            'gpa' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'major' => 'required',
            'faculty' => 'required'
        ]);

        $db = $educationData->update($validatedData);
        return apiResponseClass::sendResponse($db, 'Education updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $educationData = Educations::findOrFail($id);
        $success = $educationData->delete();

        return apiResponseClass::sendResponse($success, 'Success delete education', 200);
    }
}
