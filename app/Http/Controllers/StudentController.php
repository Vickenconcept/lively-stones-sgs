<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Classroom;
use App\Models\SessionYear;
use App\Models\Student;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

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


    public function getStudentsWithResults(Request $request)
    {
        try {
            $validator = ValidatorFacade::make($request->all(), [
                'classroom_id'     => 'required|exists:classrooms,id',
                'session_year_id'  => 'required|exists:session_years,id',
                'term_id'          => 'required|exists:terms,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid input', 'errors' => $validator->errors()], 422);
            }

            $classroomId    = (int) $request->input('classroom_id');
            $sessionYearId  = (int) $request->input('session_year_id');
            $termId         = (int) $request->input('term_id');

            $studentIds = Result::query()
                ->where('classroom_id', $classroomId)
                ->where('session_year_id', $sessionYearId)
                ->where('term_id', $termId)
                ->distinct()
                ->pluck('student_id');

            $students = Student::whereIn('id', $studentIds)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($students);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function promote(Request $request)
    {
        $request->validate([
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'term_id' => 'required|exists:terms,id',
            'session_year_id' => 'required|exists:session_years,id',
        ]);

        $termId = (int) $request->input('term_id');
        $sessionYearId = (int) $request->input('session_year_id');

        // Only allow promotion at the final term using configured order
        $finalTermOrder = \App\Models\Term::max('term_order');
        $currentTermOrder = \App\Models\Term::where('id', $termId)->value('term_order');
        if (!$finalTermOrder || !$currentTermOrder || $currentTermOrder < $finalTermOrder) {
            return back()->with('error', 'Promotion is only allowed at the end of the final term.');
        }

        $currentSession = SessionYear::find($sessionYearId);
        if (!$currentSession) {
            return back()->with('error', 'Invalid session year.');
        }

        $nextSession = $currentSession->next();
        if (!$nextSession) {
            $suggested = SessionYear::computeNextNameFrom($currentSession->name) ?? 'Next Session';
            return back()->with('error', "Next session year is missing. Please create it (e.g., {$suggested}) before promoting.");
        }

        $studentIds = $request->input('students', []);

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

        return back()->with('success', 'Students promoted successfully to the next class. Please proceed to use the next session year.');
    }


    public function destroy(Student $student)
    {
        $student->delete();
        return back();
    }
}
