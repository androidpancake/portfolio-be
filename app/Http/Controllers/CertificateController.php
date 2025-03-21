<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Models\Certificates;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Certificates::all();
        return apiResponseClass::sendResponse($data, 'Success retrieve certificate data', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'organizer' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'file' => 'nullable|file|mimes:pdf',
            'expired_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = $image->storeAs('certificates', $name, 'public');
            $validatedData['image'] = $destinationPath;
        }

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $name = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = $image->storeAs('certificates', $name, 'public');
            $validatedData['files'] = $destinationPath;
        }

        $db = Certificates::create($validatedData);
        return apiResponseClass::sendResponse($db, 'certificate created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $certificateData = Certificates::find($id)->get();

        return apiResponseClass::sendResponse($certificateData, 'Success get detail', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $certificateData = Certificates::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required',
            'organizer' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'file' => 'nullable|file|mimes:pdf',
            'expired_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id'
        ]);

        $db = $certificateData->update($validatedData);
        return apiResponseClass::sendResponse($db, 'certificate updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $certificateData = Certificates::findOrFail($id);
        $success = $certificateData->delete();

        return apiResponseClass::sendResponse($success, 'Success delete certificate', 200);
    }
}
