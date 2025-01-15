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
}
