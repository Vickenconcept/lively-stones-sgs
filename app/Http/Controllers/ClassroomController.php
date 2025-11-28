<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClassroomController extends Controller
{
    public function index()
    {
        return view('classrooms.index', ['classrooms' => Classroom::all()]);
    }
    public function create()
    {
        return view('classrooms.create');
    }
    public function store(Request $r)
    {
        Classroom::create($r->validate(['name' => 'required']));
        return redirect()->route('classrooms.index')->with('success', 'Classroom created successfully.');
    }


    public function show(Classroom $classroom, Request $request)
    {
        $termId = $request->term_id;
        $sessionYearId = $request->session_year_id;
        $batchId = $request->batch_id;

        $students = $classroom->students()
            ->with(['results' => function ($query) use ($termId, $sessionYearId, $classroom) {
                if ($termId && $sessionYearId) {
                    $query->where('term_id', $termId)
                        ->where('session_year_id', $sessionYearId)
                        ->where('classroom_id', $classroom->id); // Only show results for current classroom
                }
            }, 'batch'])
            ->when($batchId, function ($query) use ($batchId) {
                return $query->where('batch_id', $batchId);
            })
            ->paginate(10, ['*'], 'students');

        $classSubjectTerms = $classroom->classSubjectTerms()
            ->when($termId, function ($query) use ($termId) {
                $query->where('term_id', $termId);
            })
            ->when($sessionYearId, function ($query) use ($sessionYearId) {
                $query->where('session_year_id', $sessionYearId);
            })
            ->with(['subject', 'term', 'sessionYear'])
            ->paginate(10, ['*'], 'classSubjects');

        // $classSubjectTerms = $classroom->classSubjectTerms()
        //     ->with(['subject', 'term', 'sessionYear'])
        //     ->paginate(10, ['*'], 'classSubjects');

        // Only look for old students if we have both term and session year
        $oldStudents = collect();
        if ($termId && $sessionYearId) {
            // Find students who have results for this classroom but are no longer currently in this classroom
            $results = Result::where('classroom_id', $classroom->id)
                ->where('term_id', $termId)
                ->where('session_year_id', $sessionYearId)
                ->get();

            $studentIdsWithResults = $results->pluck('student_id')->unique();
            
            // Get current students in this classroom
            $currentStudentIds = $classroom->students()->pluck('id');
            
            // Old students are those who have results for this classroom but are not currently in this classroom
            $oldStudentIds = $studentIdsWithResults->diff($currentStudentIds);

            // Get the old students with their results
            $oldStudents = Student::whereIn('id', $oldStudentIds)
                ->with(['results' => function ($query) use ($termId, $sessionYearId, $classroom) {
                    $query->where('term_id', $termId)
                        ->where('session_year_id', $sessionYearId)
                        ->where('classroom_id', $classroom->id); // Only show results for this classroom
                }, 'batch'])
                ->paginate(10, ['*'], 'oldStudents');
        }

        return view('classrooms.show', compact('classroom', 'students', 'classSubjectTerms', 'oldStudents'));
    }


    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id,
            // 'capacity' => 'required|integer|min:1',
        ]);

        $classroom->update($request->only('name'));

        return redirect()->route('classrooms.index')->with('success', 'Classroom updated successfully.');
    }
    public function destroy(Classroom $classroom)
    {
        try {
            $classroom->delete();
            return back()->with('success', 'Classroom deleted successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Cannot delete classroom because there are related student or result records.');
        }
    }
}
