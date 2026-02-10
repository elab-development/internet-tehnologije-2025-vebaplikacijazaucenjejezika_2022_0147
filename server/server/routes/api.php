<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\TranslateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

Route::get('/languages', [LanguageController::class, 'index']);
Route::get('/languages/{language}', [LanguageController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::resource('courses', CourseController::class)
        ->only(['store', 'update', 'destroy']);

    Route::get('/teacher/{id}/courses', [CourseController::class, 'teacherCourses']);

    Route::resource('lessons', LessonController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);

    Route::resource('enrollments', EnrollmentController::class)
        ->only(['index', 'store', 'update']);

    Route::get('/student/{id}/enrollments', [EnrollmentController::class, 'studentEnrollments']);

    Route::resource('languages', LanguageController::class)
        ->only(['store', 'update', 'destroy']);


    Route::get('/translate', [TranslateController::class, 'translate']);
});
