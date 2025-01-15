<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Models\Contents;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $data = Contents::all();
        return apiResponseClass::sendResponse($data, '', 200);
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
}
