<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassSubjectTermController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ScratchCodeController;
use App\Http\Controllers\SessionYearController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentScoreController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BatchController;
use App\Models\Classroom;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('register', 'auth.register')->name('register');
    Route::view('register/success', 'auth.success')->name('register.success');


    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
    });
    Route::controller(PasswordResetController::class)->group(function () {
        Route::get('forgot-password', 'index')->name('password.request');
        Route::post('forgot-password', 'store')->name('password.email');
        Route::get('/reset-password/{token}', 'reset')->name('password.reset');
        Route::post('/reset-password', 'update')->name('password.update');
    });
});



// Public endpoints to support results selection BEFORE resource routes to avoid capture by students.show
Route::get('/students/with-results', [StudentController::class, 'getStudentsWithResults']);

Route::middleware(['auth'])->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/home', [AuthController::class, 'dashboard'])->name('home')->middleware('role:super-admin');


    Route::post('promote/students', [StudentController::class, 'promote'])->name('students.promote');
    Route::resource('students', StudentController::class)->middleware('permission:manage students');
    Route::resource('classrooms', ClassroomController::class)->middleware(['permission:manage classes']);
    Route::resource('subjects', SubjectController::class)->middleware('role:super-admin|admin');
    Route::post('/session-years/activate/{id}', [SessionYearController::class, 'status_update'])->name('session.active');
    Route::resource('session_years', SessionYearController::class)->middleware('role:super-admin');
    Route::resource('terms', TermController::class)->middleware('role:super-admin');
    Route::get('/class_subject_terms/{classSubjectTerm}/upload-score', [ClassSubjectTermController::class, 'uploadScoreForm'])->name('class_subject_terms.upload_score_form');
    Route::post('/class_subject_terms/{classSubjectTerm}/upload-score', [ClassSubjectTermController::class, 'uploadScore'])->name('class_subject_terms.upload_score');
    Route::post('class-subject-terms/{classSubjectTerm}/upload-scores-csv', [ClassSubjectTermController::class, 'uploadScoresExcel'])
        ->name('classSubjectTerms.uploadScoresCsv');
    Route::resource('class_subject_terms', ClassSubjectTermController::class)->middleware('role:super-admin|admin');

    // Define bulk route BEFORE resource to avoid being captured by destroy /scratch-codes/{id}
    Route::delete('/scratch-codes/bulk', [ScratchCodeController::class, 'bulkDestroy'])->name('scratch-codes.bulk-destroy')->middleware('role:super-admin');
    Route::resource('/scratch-codes', ScratchCodeController::class)->middleware('role:super-admin');
    Route::post('/results/calculate', [ResultController::class, 'calculate'])->name('results.calculate');

    Route::middleware('role:super-admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.changeRole');
        Route::put('users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
    });

    Route::resource('/batches', BatchController::class);
});


Route::get('/get-students/{classroom}', [StudentController::class, 'getStudentsByClass']);

Route::get('/students/batches/{classroomId}', [StudentController::class, 'getBatchesByClass']);

Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/results/select', function () {
        $classrooms = Cache::remember('classrooms_all', 3600, fn() => Classroom::all());
        $terms = Cache::remember('terms_all', 3600, fn() => \App\Models\Term::all());
        $sessions = Cache::remember('sessions_all', 3600, fn() => \App\Models\SessionYear::all());
        return view('results.select', compact('classrooms', 'terms', 'sessions'));
    })->name('results.select');

    Route::get('/results/student', [ResultController::class, 'viewStudentResult'])->name('results.student');
});
