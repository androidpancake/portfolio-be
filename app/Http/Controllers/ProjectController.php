<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\ProjectResource;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Integer;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Projects::with('categories', 'skills', 'detailProject')->where('status', 'published')->latest()->paginate(4);
        return apiResponseClass::sendResponse(ProjectResource::collection($data), '', 200);
    }

    function slugify($string)
    {
        $string = strtolower($string); // Replace any non-alphanumeric characters with a hyphen 
        $string = preg_replace('/[^a-z0-9-]+/', '-', $string); // Trim any leading or trailing hyphens 
        $string = trim($string, '-');
        return $string;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|unique:projects,title',
            'description' => 'required',
            'url' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required|in:draft,published,scheduled',
            'scheduled_at' => [
                'nullable',
                'date',

                function ($value, $fail) use ($request) {
                    if ($request->status === 'scheduled' && empty($value)) {
                        $fail('required to fill schedule time');
                    }
                },
            ],
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',

        ], [
            'title.required' => 'title required',
            'description.required' => 'description required',
            'url.required' => 'url required',
            'category_id.required' => 'category_id required',
            'start_date.required' => 'start_date required',
            'end_date.required' => 'end_date required',
            'status.required' => 'status required',
            'skills.required' => 'skill required'
        ]);

        $detailProjectValidatedData = $request->validate([
            'background' => 'required',
            'stack1' => 'nullable',
            'stack2' => 'nullable',
            'stack3' => 'nullable',
            'db' => 'nullable',
            'logo' => 'nullable'
        ]);

        Log::info($validatedData);
        $validatedData['slug'] = $this->slugify($validatedData['title']);

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $image->storeAs('images', $name, 'public');
                $validatedData['image'] = $destinationPath;
            }

            if ($validatedData && $detailProjectValidatedData) {

                $db = Projects::create($validatedData);
                $db->skills()->attach($request->skills);

                $detailProjectValidatedData['project_id'] = $db->id;
                $db->detailProject()->create($detailProjectValidatedData);
            }


            return apiResponseClass::sendResponse($db, 'Project created successfully', 201);
        } catch (\Throwable $th) {
            return apiResponseClass::rollback($th, $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Projects $projects)
    {
        $data = Projects::with('categories', 'skills', 'detailProject')->where('slug', $projects->slug)->first();
        // $projectById = $data->where('id', $data->id)->get();

        return apiResponseClass::sendResponse(new ProjectResource($data), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Projects $projects)
    {
        $projects = Projects::findOrFail($projects->id);

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'nullable',
            'description' => 'required',
            'url' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:draft,published,scheduled',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $detailProjectValidatedData = $request->validate([
            'project_id' => 'exists:projects,id',
            'background' => 'nullable',
            'stack1' => 'nullable',
            'stack2' => 'nullable',
            'stack3' => 'nullable',
            'db' => 'nullable',
            'logo' => 'nullable'
        ]);

        $validatedData['slug'] = $this->slugify($validatedData['title']);

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $image->storeAs('images', $name, 'public');
                $validatedData['image'] = $destinationPath;
            }

            $db = $projects->where('id', $projects->id)->update($validatedData);

            if ($request->skills) {
                $db->skills()->sync($request->skills);
            }

            if ($detailProjectValidatedData) {
                $projects->detailProject()->updateOrCreate($detailProjectValidatedData);
            }

            $success = $projects->title . ' Success Update Data';

            return apiResponseClass::sendResponse($success, 'Project updated successfully', 201);
        } catch (\Throwable $th) {
            return apiResponseClass::sendError($th->getMessage(), 'Failed to update data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projects $projects)
    {
        $projectData = Projects::findOrFail($projects->id);
        $projectData->detailProject()->delete();
        $projectData->skills()->detach();
        $projectData->delete();

        return apiResponseClass::sendResponse($projectData->title . 'Deleted', 'Success delete project data', 200);
    }
}
