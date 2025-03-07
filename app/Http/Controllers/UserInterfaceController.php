<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProjectResource;
use App\Models\Categories;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class UserInterfaceController extends Controller
{
    public function countProjectData()
    {
        $data = DB::table('projects')->get();
        $res = $data->count();

        return response()->json([
            'total' => $res
        ]); 
    }
    
    public function getProjectDataLimit(Request $request)
    {
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);

        $data = Projects::paginate($limit, ['*'], 'page', $page)->get();
        return apiResponseClass::sendResponse(ProjectResource::collection($data), '', 200);
    }

    public function getCategoryProjectDropdown()
    {
        $data = Categories::with('project')->get();

        return apiResponseClass::sendResponse(CategoryResource::collection($data), '', 200);
    }

    public function getProjectWithFilter(Request $request)
    {
        $category = $request->query('category', []);
        $sort = $request->query('sort', 'desc');
        $skill = $request->query('skill', []);
        $sortByName = $request->query('sortByName', 'desc');
        $sortByAdded = $request->query('sortByAdded', 'desc');
        $search = $request->query('title');

        $query = Projects::with('categories', 'skills', 'detailProject');

        if($search)
        {
            $query->where('title', 'LIKE', '%' . $search . '%');
        }

        if($category)
        {
            $categories = is_array($category) ? $category : explode(',', $category);
            $query->whereIn('category_id', $categories);
        }

        if ($skill) {
            $skills = is_array($skill) ? $skill : explode(',', $skill);
            $query->whereHas('skills', function($q) use ($skills){
                $q->whereIn('skills.id', $skills);
            });
        }

        if ($sort) {
            $query->orderBy('end_date', $sort);
        }

        if($sortByName) {
            $query->orderBy('title', $sortByName);
        }

        if($sortByAdded) {
            $query->orderBy('created_at', $sortByAdded);
        }
        
        try {
            //code...
            $data = $query->paginate(5);
        } catch (\Throwable $th) {
            //throw $th;
            return apiResponseClass::sendError($th, 'Failed to get data', 404);
        }

        return apiResponseClass::sendResponse($data, '', 200);
    }

}
