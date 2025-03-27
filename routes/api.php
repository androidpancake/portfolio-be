<?php

use App\Http\Controllers\AuthController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'registerUserApp');
    Route::post('token', 'getTokenApp');
    Route::post('admin/token', 'getTokenAdmin');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('approve', 'approveUserApp')->middleware('auth:sanctum');
});

Route::controller(ProjectController::class)->group(function () {
    Route::post('projects', 'store')->middleware(['auth:sanctum', 'abilities:admin']);
    Route::patch('projects/{projects}', 'update')->middleware(['auth:sanctum', 'abilities:admin']);
    Route::delete('projects/{projects}', 'destroy')->middleware(['auth:sanctum', 'abilities:admin']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('projects', 'index');
        Route::get('projects/slug/{projects:slug}', 'show');
    });
});

Route::controller(CategoryController::class)->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::post('categories', 'store');
        Route::patch('categories/{categories}', 'update');
        Route::delete('categories/{categories}', 'destroy');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('categories', 'index');
    });
});

Route::controller(SkillController::class)->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::post('skills', 'store');
        Route::patch('skills/{skills}', 'update');
        Route::delete('skills/{skills}', 'destroy');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('skills', 'index');
    });
});

Route::controller(EducationController::class)->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::post('educations', 'store');
        Route::patch('educations/{educations}', 'update');
        Route::delete('educations/{educations}', 'destroy');
    });
    Route::middleware(['auth:sanctum', 'abilities:get-data'])->group(function () {
        Route::get('educations', 'index');
    });
});

Route::controller(CertificateController::class)->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::post('certifications', 'store');
        Route::patch('certifications/{certifications}', 'update');
        Route::delete('certifications/{certifications}', 'destroy');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('certifications', 'index');
    });
});

Route::controller(MenusController::class)->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::post('menus', 'store');
        Route::patch('menus/{menus}', 'update');
        Route::delete('menus/{menus}', 'destroy');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('menus', 'index');
    });
});

Route::controller(ContentController::class)->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::post('content', 'store');
        Route::patch('content/{content}', 'update');
        Route::delete('content/{content}', 'destroy');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('content', 'index');
    });
});

// detail
Route::post('projects/detail', [ProjectDetailController::class, 'store'])->middleware(['auth-sanctum', 'abilities:admin']);

// search
Route::get('search/project', [SearchController::class, 'searchProject']);

// Route::get('search', function)
Route::controller(UserInterfaceController::class)->group(function () {
    Route::get('ui/projects/count', 'countProjectData');
    Route::get('ui/projects/pagelimit/{limit}/{page}', 'getProjectDataLimit');
    Route::get('ui/projects/filter', 'getProjectWithFilter');
})->middleware(['auth:sanctum']);
