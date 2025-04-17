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

    //     foreach ($students as $student) {
    //         $scores = StudentScore::where('student_id', $student->id)
    //             ->where('term_id', $termId)
    //             ->where('session_year_id', $sessionYearId)
    //             ->get();

    //         $total = $scores->sum('total_score');
    //         $subjectCount = $scores->count();
    //         $average = $subjectCount > 0 ? round($total / $subjectCount, 2) : 0;

    //         // Simple grading logic
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
    //                 'position' => null,
    //                 'c_avarage' => null,
    //                 'cumulative' => null,
    //                 'c_position' => null,
    //             ]
    //         );


    //         // cummulative
    //         $cumulativeScores = StudentScore::where('student_id', $student->id)
    //             ->where('session_year_id', $sessionYearId)
    //             ->get()
    //             ->groupBy('subject_id');

    //         $cumulativeTotal = 0;
    //         $subjectCount = count($cumulativeScores);

    //         foreach ($cumulativeScores as $subjectScores) {
    //             $cumulativeTotal += $subjectScores->sum('total_score');
    //         }

    //         $cumulativeAverage = $subjectCount > 0 ? round($cumulativeTotal / $subjectCount, 2) : 0;

    //         $cumulativeGrade = match (true) {
    //             $cumulativeAverage >= 70 => 'A',
    //             $cumulativeAverage >= 60 => 'B',
    //             $cumulativeAverage >= 50 => 'C',
    //             $cumulativeAverage >= 40 => 'D',
    //             default => 'F',
    //         };

    //         // Update cumulative values
    //         Result::where('student_id', $student->id)
    //             ->where('classroom_id', $classroomId)
    //             ->where('term_id', $termId)
    //             ->where('session_year_id', $sessionYearId)
    //             ->update([
    //                 'c_avarage' => $cumulativeAverage,
    //                 'cumulative' => $cumulativeTotal,
    //                 'c_position' => null,
    //             ]);
    //     }







    //     // 👉 Assign positions based on total_score
    //     $results = Result::where('classroom_id', $classroomId)
    //         ->where('term_id', $termId)
    //         ->where('session_year_id', $sessionYearId)
    //         ->orderByDesc('total_score')
    //         ->get();

    //     $position = 1;
    //     $lastScore = null;
    //     $sameRank = 1;

    //     foreach ($results as $result) {
    //         if ($lastScore !== null && $result->total_score === $lastScore) {
    //             $ordinalPosition = $this->ordinal($sameRank);
    //         } else {
    //             $ordinalPosition = $this->ordinal($position);
    //             $sameRank = $position;
    //         }

    //         $result->position = $ordinalPosition;
    //         $result->save();

    //         $lastScore = $result->total_score;
    //         $position++;
    //     }


    //     // // 👉 Assign cumulative positions
    //     $cumulativeResults = Result::where('classroom_id', $classroomId)
    //         ->where('term_id', $termId)
    //         ->where('session_year_id', $sessionYearId)
    //         ->orderByDesc('cumulative')
    //         ->get();

    //     $cPos = 1;
    //     $lastCumulative = null;
    //     $sameCRank = 1;

    //     foreach ($cumulativeResults as $result) {
    //         if ($lastCumulative !== null && $result->cumulative === $lastCumulative) {
    //             $cOrdinal = $this->ordinal($sameCRank);
    //         } else {
    //             $cOrdinal = $this->ordinal($cPos);
    //             $sameCRank = $cPos;
    //         }

    //         $result->c_position = $cOrdinal;
    //         $result->save();

    //         $lastCumulative = $result->cumulative;
    //         $cPos++;
    //     }


    //     return back()->with('success', 'Results calculated, saved, and positions assigned successfully.');
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

        $students = Student::whereHas('classroom', fn($q) => $q->where('classroom_id', $classroomId))->get();

        $cumulativeData = []; // ➕ used for assigning cumulative positions later

        foreach ($students as $student) {
            // TERM TOTAL & AVERAGE (for current term)
            $scores = StudentScore::where('student_id', $student->id)
                ->where('term_id', $termId)
                ->where('session_year_id', $sessionYearId)
                ->get();

            $termTotal = $scores->sum('total_score');
            $termSubjectCount = $scores->count();
            $termAverage = $termSubjectCount > 0 ? round($termTotal / $termSubjectCount, 2) : 0;

            $termGrade = match (true) {
                $termAverage >= 70 => 'A',
                $termAverage >= 60 => 'B',
                $termAverage >= 50 => 'C',
                $termAverage >= 40 => 'D',
                default => 'F',
            };

            Result::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'classroom_id' => $classroomId,
                    'term_id' => $termId,
                    'session_year_id' => $sessionYearId,
                ],
                [
                    'total_score' => $termTotal,
                    'average' => $termAverage,
                    'grade' => $termGrade,
                    'position' => null,
                    'c_avarage' => null,
                    'cumulative' => null,
                    'c_position' => null,
                ]
            );

            // ➕ CUMULATIVE (across all 3 terms)
            $allScores = StudentScore::where('student_id', $student->id)
                ->where('session_year_id', $sessionYearId)
                ->get();

            $cumulativeTotal = $allScores->sum('total_score');
            $subjectCount = $allScores->count();
            $cumulativeAverage = $subjectCount > 0 ? round($cumulativeTotal / $subjectCount, 2) : 0;

            $cumulativeGrade = match (true) {
                $cumulativeAverage >= 70 => 'A',
                $cumulativeAverage >= 60 => 'B',
                $cumulativeAverage >= 50 => 'C',
                $cumulativeAverage >= 40 => 'D',
                default => 'F',
            };

            // Save cumulative data (to be used for position later)
            $cumulativeData[] = [
                'student_id' => $student->id,
                'cumulative' => $cumulativeTotal,
            ];

            // Update ALL terms’ results with same cumulative (optional: only latest term if you want)
            Result::where('student_id', $student->id)
                ->where('classroom_id', $classroomId)
                ->where('session_year_id', $sessionYearId)
                ->update([
                    'c_avarage' => $cumulativeAverage,
                    'cumulative' => $cumulativeTotal,
                    'c_position' => null,
                ]);
        }

        // ➕ Assign term positions based on total_score (per term)
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

        // ✅ Assign cumulative position ONCE per student
        usort($cumulativeData, fn($a, $b) => $b['cumulative'] <=> $a['cumulative']);

        $cPos = 1;
        $lastCumulative = null;
        $sameCRank = 1;

        foreach ($cumulativeData as $data) {
            $studentId = $data['student_id'];
            $cumulative = $data['cumulative'];

            if ($lastCumulative !== null && $cumulative === $lastCumulative) {
                $cOrdinal = $this->ordinal($sameCRank);
            } else {
                $cOrdinal = $this->ordinal($cPos);
                $sameCRank = $cPos;
            }

            // Only update cumulative position on the 3rd term result row (or any term you choose)
            Result::where('student_id', $studentId)
                ->where('classroom_id', $classroomId)
                ->where('session_year_id', $sessionYearId)
                ->orderByDesc('term_id') // assumes 3rd term is highest
                ->first()?->update([
                    'c_position' => $cOrdinal
                ]);

            $lastCumulative = $cumulative;
            $cPos++;
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


        if (!auth()->check()) {
            $code = ScratchCode::where('code', $inputCode)->first();

            if (!$code) {
                return back()->withErrors(['code' => 'Scratch code not found.']);
            }

            if ($code->uses_left <= 0) {
                return back()->withErrors(['code' => 'This scratch code has already been fully used.']);
            }

            if ($code->student_id && $code->student_id != $studentId) {
                return back()->withErrors(['code' => 'This scratch code is already linked to another student.']);
            }

            if (is_null($code->student_id)) {
                $code->student_id = $studentId;
                $code->term_id = $termId;
                $code->session_year_id = $sessionYearId;
            } else {
                if ($code->term_id != $termId || $code->session_year_id != $sessionYearId) {
                    return back()->withErrors(['code' => 'This scratch code is not valid for the selected term or session year.']);
                }
            }

            $code->uses_left--;
            $code->save();
        }






        $student = Student::with('classroom')->findOrFail($studentId);



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

        // Only if selected term is 3rd term, fetch total scores for 1st and 2nd
        $groupedScores = null;
        $totalSum = null;
        $averageScore = null;
        $cummulativePosition = null;

        if ($termId == 3) {
            $terms = [1 => 'first', 2 => 'second', 3 => 'third'];
            $groupedScores = [];

            foreach ($terms as $termIdKey => $termLabel) {
                $scores = StudentScore::with('subject')
                    ->where('student_id', $studentId)
                    ->where('term_id', $termIdKey)
                    ->where('session_year_id', $sessionYearId)
                    ->get();

                foreach ($scores as $score) {
                    $subjectId = $score->subject_id;
                    $subjectName = $score->subject->name;

                    if (!isset($groupedScores[$subjectId])) {
                        $groupedScores[$subjectId] = [
                            'subject' => $subjectName,
                            'first' => 0,
                            'second' => 0,
                            'third' => 0,
                        ];
                    }

                    $groupedScores[$subjectId][$termLabel] = $score->total_score;
                }
            }


            // $subjectCount = count($groupedScores);

            // foreach ($groupedScores as $subjectScores) {
            //     $total = $subjectScores['first'] + $subjectScores['second'] + $subjectScores['third'];
            // }

        }


        $totalSum = optional($result)->cumulative;

        $averageScore = optional($result)->c_avarage;
        $cummulativePosition = optional($result)->c_position;

        return view('results.student', compact(
            'student',
            'result',
            'scores',
            'termId',
            'sessionYearId',
            'groupedScores',
            'totalSum',
            'averageScore',
            'cummulativePosition'
        ));

        // return view('results.student', compact('student', 'result', 'scores', 'termId', 'sessionYearId'));
    }
}
