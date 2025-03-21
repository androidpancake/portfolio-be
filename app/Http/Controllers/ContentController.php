<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Models\Contents;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $data = Contents::paginate(5);
        return apiResponseClass::sendResponse($data, 'Success', 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $db = Contents::create($validatedData);
        return apiResponseClass::sendResponse($db, 'Success', 201);
    }

    public function show(Contents $contents)
    {
        $contentData = Contents::findOrFail($contents);
        return apiResponseClass::sendResponse($contentData, 'Success retrieved content' . $contentData->title, 200);
    }

    public function update(Request $request, Contents $contents)
    {
        $contentData = Contents::findOrFail($contents->id);

        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $db = $contentData->update($validatedData);
        return apiResponseClass::sendResponse($db->title, 'Success', 201);
    }

    public function destroy(Contents $contents)
    {
        $contents->delete();

        return apiResponseClass::sendResponse($contents->title, 'Success Delete Content', 201);
    }
}
