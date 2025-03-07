<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDetailController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserInterfaceController;
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
Route::get('content', [ContentController::class, 'index']);
Route::post('content', [ContentController::class, 'store']);
Route::get('projects/detail/{id}', [ProjectDetailController::class, 'show']);
Route::post('projects/detail', [ProjectDetailController::class, 'store']);

Route::get('projects/slug/{slug}', [ProjectController::class, 'showBySlug']);
Route::get('projects/filter', [ProjectController::class, 'filter']);

// search
Route::get('search/project', [SearchController::class, 'searchProject']);

// count
Route::get('projects/counts', [ProjectController::class, 'counts']);

// Route::get('search', function)
Route::get('ui/projects/count', [UserInterfaceController::class, 'countProjectData']);
Route::get('ui/projects/pagelimit/{limit}/{page}', [UserInterfaceController::class, 'getProjectDataLimit']);
Route::get('ui/projects/filter', [UserInterfaceController::class, 'getProjectWithFilter']);
