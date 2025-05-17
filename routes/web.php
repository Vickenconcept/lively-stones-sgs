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
use App\Models\Classroom;
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



Route::middleware(['auth'])->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');

    
    Route::post('promote/students', [StudentController::class, 'promote'])->name('students.promote');
    Route::resource('students', StudentController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('subjects', SubjectController::class);
    Route::post('/session-years/activate/{id}', [SessionYearController::class, 'status_update'])->name('session.active');
    Route::resource('session_years', SessionYearController::class);
    Route::resource('terms', TermController::class);
    Route::get('/class_subject_terms/{classSubjectTerm}/upload-score', [ClassSubjectTermController::class, 'uploadScoreForm'])->name('class_subject_terms.upload_score_form');
    Route::post('/class_subject_terms/{classSubjectTerm}/upload-score', [ClassSubjectTermController::class, 'uploadScore'])->name('class_subject_terms.upload_score');
    Route::resource('class_subject_terms', ClassSubjectTermController::class);

    Route::resource('/scratch-codes', ScratchCodeController::class);
    Route::post('/results/calculate', [ResultController::class, 'calculate'])->name('results.calculate');
});


Route::get('/get-students/{classroom}', [StudentController::class, 'getStudentsByClass']);

Route::get('/results/select', function () {
    return view('results.select', [
        'classrooms' => Classroom::all(),
        'terms' => \App\Models\Term::all(),
        'sessions' => \App\Models\SessionYear::all(),
    ]);
})->name('results.select');


Route::get('/results/student', [ResultController::class, 'viewStudentResult'])
    ->name('results.student');
