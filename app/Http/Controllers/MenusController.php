<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Menus::where('is_display', '1')->orderBy('order', 'asc')->get();
        return apiResponseClass::sendResponse($data, '', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required',
            'url' => 'required',
            'order' => 'required|unique:menuses,order',
            'is_display' => 'required|in:1,0'
        ], [
            'title.required' => 'title required',
            'url' => 'url/path required',
            'order' => 'order required or exists',
            'is_display' => 'menu is_display must be filled'
        ]);

        if ($validate) {
            try {
                $db = Menus::create($validate);
                return apiResponseClass::sendResponse($db, 'Menu created successfully', 201);
            } catch (\Throwable $th) {
                return apiResponseClass::rollback([$th->getMessage(), throw ValidationException::withMessages($validate)], 'Error creating menu');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Menus $id)
    {
        return apiResponseClass::sendResponse($id, '', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menus $menus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menus $menus)
    {

        $validate = $request->validate([
            'title' => 'required|unique',
            'url' => 'required',
            'order' => 'required|unique:menuses,order',
            'is_display' => 'required|in:1,0'
        ], [
            'title.required' => 'title required',
            'url' => 'url/path required',
            'order' => 'order required or exists',
            'is_display' => 'menu is_display must be filled'
        ]);

        if ($validate) {
            try {
                $db = $menus->update($validate);
                return apiResponseClass::sendResponse($db, 'Menu updated successfully', 201);
            } catch (\Throwable $th) {
                return apiResponseClass::rollback([$th->getMessage(), throw ValidationException::withMessages($validate)], 'Error creating menu');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menus $menus)
    {
        $success = $menus->delete();

        return apiResponseClass::sendResponse($success, 'Success Delete Menu', 200);
    }
}
