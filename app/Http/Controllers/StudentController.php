<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->class_id;
        $batchId = $request->batch_id;
        $search = $request->search;

        $students = Student::with(['classroom', 'batch'])
            ->when($classId, function ($query) use ($classId) {
                return $query->where('classroom_id', $classId);
            })
            ->when($batchId, function ($query) use ($batchId) {
                return $query->where('batch_id', $batchId);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(20);

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        // Generate a unique registration number (e.g., REG + next id or random)
        $latestStudent = Student::orderBy('id', 'desc')->first();
        $nextId = $latestStudent ? $latestStudent->id + 1 : 1;
        $registrationNumber = 'REG' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        return view('students.create', compact('classrooms', 'registrationNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'registration_number' => 'required|unique:students',
            'classroom_id' => 'required|exists:classrooms,id',
            'batch_id' => 'required|exists:batches,id'
        ]);

        Student::create($request->all());
        return redirect()->route('students.index')->with('success', 'Student registered successfully.');
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::all();
        $batches = Batch::all();
        return view('students.edit', compact('student', 'classrooms', 'batches'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'registration_number' => 'required|unique:students,registration_number,' . $student->id,
            'classroom_id' => 'required|exists:classrooms,id',
            'batch_id' => 'required|exists:batches,id'
        ]);

        $student->update($request->all());
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function getStudentsByClass($classroomId)
    {
        $students = Student::where('classroom_id', $classroomId)->get(['id', 'name']);
        return response()->json($students);
    }

    public function getBatchesByClass($classroomId)
    {
        $batches = Batch::all(['id', 'name']);
        return response()->json($batches);
    }

    public function promote(Request $request)
    {
        $studentIds = $request->input('students', []);

        // dd($studentIds);
        foreach ($studentIds as $id) {
            $student = Student::find($id);
            if (!$student) continue;

            $currentClass = $student->classroom;
            if (!$currentClass || !$currentClass->promotion_order) continue;

            $nextClass = Classroom::where('promotion_order', $currentClass->promotion_order + 1)->first();

            if ($nextClass) {
                $student->classroom_id = $nextClass->id;
                $student->save();
            }
        }

        return back()->with('success', 'Students promoted successfully!');
    }


    public function destroy(Student $student)
    {
        $student->delete();
        return back();
    }
}
