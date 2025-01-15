<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('projects', ProjectController::class);
Route::resource('categories', CategoryController::class);
Route::resource('skills', SkillController::class);
Route::resource('educations', EducationController::class);
Route::resource('certifications', CertificateController::class);
Route::resource('menus', MenusController::class);

// search
Route::get('search/project', [SearchController::class, 'searchProject']);
