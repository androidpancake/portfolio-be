<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\ProjectResource;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Projects::with('categories', 'skills', 'detailProject')->latest()->paginate(4);
        return apiResponseClass::sendResponse(ProjectResource::collection($data), '', 200);
    }

    public function filter(Request $request)
    {
        $category = $request->query('category', []);
        $sort = $request->input('sort', 'desc');
        $skill = $request->input('skill', []);

        $dt = Projects::with('categories', 'skills', 'detailProject');

        if($category)
        {
            $categories = is_array($category) ? $category : explode(',', $category);
            $dt->where('category_id', $categories);
        }

        // $query = DB::table('projects')
        // ->whereIn('category_id', $category)
        // ->leftJoin('projects_skills', 'projects.id', '=', 'projects_skills.projects_id');

        // if($category)
        // {
        //     $categories = is_array($category) ? $category : explode(',', $category);
        //     $query->whereIn('projects.category_id', $categories);
        // }

        // if ($skill) {
        //     $skills = is_array($skill) ? $skill : explode(',', $skill);
        //     $query->whereIn('projects_skills.skill_id', $skills);
        // }

        // if ($sort) {
        //     $query->orderBy('projects.end_date', $sort);
        // }

        $data = $dt->get();

        return apiResponseClass::sendResponse(ProjectResource::collection($data), '', 200);
    }

    public function counts()
    {
        try {
            //code...
            $data = Projects::with('categories', 'skills')->get();
            return apiResponseClass::sendResponse($data, '', 200);
        } catch (\Throwable $th) {
            //throw $th;
            $th->getMessage();
        }
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
            'slug' => 'nullable',
            'description' => 'required',
            'url' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required',
            'end_date' => 'required',
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',

        ]);

        $detailProjectValidatedData = $request->validate([
            'project_id' => 'exists:projects,id',
            'background' => 'required',
            'stack1' => 'required',
            'stack2' => 'nullable',
            'stack3' => 'nullable',
            'db' => 'required',
            'logo' => 'required'
        ]);

        // dd($validatedData['slug']);

        $validatedData['slug'] = $this->slugify($validatedData['title']);


        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $image->storeAs('images', $name, 'public');
                $validatedData['image'] = $destinationPath;
            }

            $db = Projects::create($validatedData);
            $db->skills()->attach($request->skills);
            $db->detailProject()->attach($detailProjectValidatedData);

            return apiResponseClass::sendResponse($db, 'Project created successfully', 201);
        } catch (\Throwable $th) {
            return apiResponseClass::rollback($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show() {}

    public function showBySlug(string $slug)
    {
        $data = Projects::with('categories', 'skills', 'detailProject')->where('slug', $slug)->first();
        // $projectById = $data->where('id', $data->id)->get();


        return apiResponseClass::sendResponse(new ProjectResource($data), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Projects::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'nullable|unique:projects,title',
            'slug' => 'nullable',
            'description' => 'required',
            'url' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
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

            $db = $data->whereId($data->id)->update($validatedData);
            $db->skills()->attach($request->skills);
            $db->detailProject()->attach($detailProjectValidatedData);

            return apiResponseClass::sendResponse($data, 'Project updated successfully', 201);
        } catch (\Throwable $th) {
            return apiResponseClass::sendError($th);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
