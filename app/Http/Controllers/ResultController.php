<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\ScratchCode;
use App\Models\Student;
use App\Models\StudentScore;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with('student', 'classroom', 'subject')->paginate(10);
        return view('results.index', compact('results'));
    }

    // public function calculate(Request $request)
    // {
    //     $request->validate([
    //         'classroom_id' => 'required|exists:classrooms,id',
    //         'term_id' => 'required|exists:terms,id',
    //         'session_year_id' => 'required|exists:session_years,id',
    //     ]);

    //     $classroomId = $request->classroom_id;
    //     $termId = $request->term_id;
    //     $sessionYearId = $request->session_year_id;

    //     // Get all students in the class
    //     $students = Student::whereHas('classroom', fn($q) => $q->where('classroom_id', $classroomId))->get();
    //     // dd($students);

    //     foreach ($students as $student) {
    //         $scores = StudentScore::where('student_id', $student->id)
    //             ->where('term_id', $termId)
    //             ->where('session_year_id', $sessionYearId)
    //             ->get();

    //         $total = $scores->sum('total_score');
    //         $subjectCount = $scores->count();
    //         $average = $subjectCount > 0 ? round($total / $subjectCount, 2) : 0;

    //         // Simple grading logic (you can change this)
    //         if ($average >= 70) {
    //             $grade = 'A';
    //         } elseif ($average >= 60) {
    //             $grade = 'B';
    //         } elseif ($average >= 50) {
    //             $grade = 'C';
    //         } elseif ($average >= 40) {
    //             $grade = 'D';
    //         } else {
    //             $grade = 'F';
    //         }

    //         Result::updateOrCreate(
    //             [
    //                 'student_id' => $student->id,
    //                 'classroom_id' => $classroomId,
    //                 'term_id' => $termId,
    //                 'session_year_id' => $sessionYearId,
    //             ],
    //             [
    //                 'total_score' => $total,
    //                 'average' => $average,
    //                 'grade' => $grade,
    //                 'position' => null, // You can calculate this separately
    //             ]
    //         );
    //     }

    //     return back()->with('success', 'Results calculated and saved successfully.');
    // }


    public function calculate(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'term_id' => 'required|exists:terms,id',
            'session_year_id' => 'required|exists:session_years,id',
        ]);

        $classroomId = $request->classroom_id;
        $termId = $request->term_id;
        $sessionYearId = $request->session_year_id;


        // Get all students in the class
        $students = Student::whereHas('classroom', fn($q) => $q->where('classroom_id', $classroomId))->get();

        foreach ($students as $student) {
            $scores = StudentScore::where('student_id', $student->id)
                ->where('term_id', $termId)
                ->where('session_year_id', $sessionYearId)
                ->get();

            $total = $scores->sum('total_score');
            $subjectCount = $scores->count();
            $average = $subjectCount > 0 ? round($total / $subjectCount, 2) : 0;

            // Simple grading logic
            if ($average >= 70) {
                $grade = 'A';
            } elseif ($average >= 60) {
                $grade = 'B';
            } elseif ($average >= 50) {
                $grade = 'C';
            } elseif ($average >= 40) {
                $grade = 'D';
            } else {
                $grade = 'F';
            }

            Result::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'classroom_id' => $classroomId,
                    'term_id' => $termId,
                    'session_year_id' => $sessionYearId,
                ],
                [
                    'total_score' => $total,
                    'average' => $average,
                    'grade' => $grade,
                    'position' => null, // We'll set this below
                ]
            );
        }

        // 👉 Assign positions based on total_score
        $results = Result::where('classroom_id', $classroomId)
            ->where('term_id', $termId)
            ->where('session_year_id', $sessionYearId)
            ->orderByDesc('total_score')
            ->get();

        $position = 1;
        $lastScore = null;
        $sameRank = 1;

        foreach ($results as $result) {
            if ($lastScore !== null && $result->total_score === $lastScore) {
                $ordinalPosition = $this->ordinal($sameRank);
            } else {
                $ordinalPosition = $this->ordinal($position);
                $sameRank = $position;
            }

            $result->position = $ordinalPosition;
            $result->save();

            $lastScore = $result->total_score;
            $position++;
        }

        return back()->with('success', 'Results calculated, saved, and positions assigned successfully.');
    }


    private function ordinal($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }


    // public function showStudentResult(Request $request, $studentId)
    // {
    //     $termId = $request->term_id;
    //     $sessionYearId = $request->session_year_id;

    //     $student = Student::with('classroom')->findOrFail($studentId);

    //     // dd($student);
    //     $results = Result::with('term', 'sessionYear')
    //         ->where('student_id', $studentId)
    //         ->when($termId, fn($query) => $query->where('term_id', $termId))
    //         ->when($sessionYearId, fn($query) => $query->where('session_year_id', $sessionYearId))
    //         ->orderBy('session_year_id')
    //         ->orderBy('term_id')
    //         ->get();

    //     $scores = StudentScore::with('subject', 'term', 'sessionYear')
    //         ->where('student_id', $studentId)
    //         ->when($termId, fn($query) => $query->where('term_id', $termId))
    //         ->when($sessionYearId, fn($query) => $query->where('session_year_id', $sessionYearId))
    //         ->orderBy('session_year_id')
    //         ->orderBy('term_id')
    //         ->orderBy('subject_id')
    //         ->get();

    //     return view('results.student', compact('student', 'results', 'scores', 'termId', 'sessionYearId'));
    // }

    public function viewStudentResult(Request $request)
    {
        $request->validate([
            'term_id' => 'required|exists:terms,id',
            'session_year_id' => 'required|exists:session_years,id',
        ]);

        $studentId = $request->student_id;
        $termId = $request->term_id;
        $sessionYearId = $request->session_year_id;
        $inputCode = $request->scratch_card_code;


        $code = ScratchCode::where('code', $inputCode)->first();

        if (!$code || $code->uses_left <= 0) {
            return back()->withErrors(['code' => 'Invalid or already used scratch code.']);
        }

        if ($code->student_id && $code->student_id != $studentId) {
            return back()->withErrors(['code' => 'This scratch code has already been used by another student.']);
        }

        if (is_null($code->student_id)) {
            $code->student_id = $studentId;
        }

        $code->uses_left--;
        $code->save();



        $student = Student::with('classroom')->findOrFail($studentId);
        // $student = Student::with('classroom')->findOrFail($studentId);




        $result = Result::with('term', 'sessionYear')
            ->where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('session_year_id', $sessionYearId)
            ->first();

        $scores = StudentScore::with('subject')
            ->where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('session_year_id', $sessionYearId)
            ->orderBy('subject_id')
            ->get();

        // dd($scores);

        return view('results.student', compact('student', 'result', 'scores', 'termId', 'sessionYearId'));
    }

}
