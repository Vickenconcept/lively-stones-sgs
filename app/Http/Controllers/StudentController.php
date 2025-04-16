<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->class_id;

        $students = Student::with('classroom')
            ->when($classId, function ($query, $classId) {
                return $query->where('classroom_id', $classId);
            })
            ->paginate(20);

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return view('students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'registration_number' => 'required|unique:students',
            'classroom_id' => 'required'
        ]);

        Student::create($request->all());
        return redirect()->route('students.index');
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::all();
        return view('students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $student->update($request->all());
        return redirect()->route('students.index');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return back();
    }
}
