<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\ProjectDetailResource;
use App\Models\ProjectDetail;
use Illuminate\Http\Request;

class ProjectDetailController extends Controller
{
    public function show(String $id)
    {
        $data = ProjectDetail::where('project_id', $id)->first();

        return apiResponseClass::sendResponse(ProjectDetailResource::collection($data), '', 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'projects_id' => 'exists:projects,id',
            'background' => 'required',
            'stack1' => 'required',
            'stack2' => 'nullable',
            'stack3' => 'nullable',
            'db' => 'required',
            'logo' => 'nullable'
        ]);

        $db = ProjectDetail::create($validatedData);

        return apiResponseClass::sendResponse($db, 'Detail Project Added', 200);
    }

    public function destroy(ProjectDetail $projectDetail) {}
}
