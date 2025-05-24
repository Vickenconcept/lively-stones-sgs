<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Mail\WelcomeMail;
use App\Models\SessionYear;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(CreateUserRequest $request)
    {

        $requestData = $request->validated();
        $requestData['is_admin'] = 1;

        $user = User::create($requestData);
        
        $user->assignRole('super-admin');

        return redirect()->to('login');
    }

    // public function login(CreateUserRequest $request)
    // {
    //     if (!Auth::attempt($request->only(['email', 'password']))) {
    //         return $request->wantsJson()
    //             ? Response::api('Invalid Credentials', Response::HTTP_BAD_REQUEST)
    //             : back()->with('error', 'Invalid Credentials');
    //     }

    //     $activeSession = SessionYear::where('is_active', '1')->first();

    //     if ($activeSession) {
    //         session()->put('session_year_id', $activeSession->id);
    //     }

    //     return redirect()->intended(route('home'));
    // }


    public function login(CreateUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $request->wantsJson()
                ? Response::api('Invalid Credentials', Response::HTTP_BAD_REQUEST)
                : back()->with('error', 'Invalid Credentials');
        }

        $activeSession = SessionYear::where('is_active', '1')->first();

        if ($activeSession) {
            session()->put('session_year_id', $activeSession->id);
        }

        // Redirect based on role
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            return redirect()->route('home');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('classrooms.index');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('classrooms.index');
        }

        // Fallback route
        return redirect()->route('home');
    }




    public function logout()
    {
        Auth::logout();

        return to_route('login');
    }

    public function dashboard()
    {
        $studentsCount = \App\Models\Student::count();
        $subjectsCount = \App\Models\Subject::count();
        $classesCount = \App\Models\Classroom::count();
        return view('dashboard', compact('studentsCount', 'subjectsCount', 'classesCount'));
    }
}
