<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Resources\ProjectResource;
use App\Models\Projects;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchProject(Request $request)
    {
        $query = Projects::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('skills')) {
            $skills = $request->input('skills');
            $query->whereHas('skills', function ($q) use ($skills) {
                $q->where('skills.id', $skills);
            });
        }

        $query->with('categories', 'skills');
        $data = $query->get();

        try {
            //code...
            if ($data->isEmpty()) {
                return apiResponseClass::sendResponse([], 'No data found', 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return apiResponseClass::sendError($th->getMessage(), 'Error getting data', 404);
        }

        return apiResponseClass::sendResponse(ProjectResource::collection($data), '', 200);
    }
}
