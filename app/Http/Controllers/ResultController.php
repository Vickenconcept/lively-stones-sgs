<?php

namespace App\Http\Controllers;

use App\Models\ClassSubjectTerm;
use App\Models\Result;
use App\Models\ScratchCode;
use App\Models\Student;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    //     // Get all batches for this classroom
    //     $batches = \App\Models\Batch::where('classroom_id', $classroomId)->get();

    //     foreach ($batches as $batch) {
    //         // Get students in this batch
    //         $students = Student::where('classroom_id', $classroomId)
    //             ->where('batch_id', $batch->id)
    //             ->get();

    //         $cumulativeData = []; // Used for assigning cumulative positions later

    //         foreach ($students as $student) {
    //             $scores = StudentScore::where('student_id', $student->id)
    //                 ->where('term_id', $termId)
    //                 ->where('session_year_id', $sessionYearId)
    //                 ->get();

    //             $totalScore = $scores->sum('total_score');
    //             $averageScore = $scores->avg('total_score');
    //             $subjectCount = $scores->count();

    //             if ($subjectCount > 0) {
    //                 $cumulativeData[] = [
    //                     'student_id' => $student->id,
    //                     'total_score' => $totalScore,
    //                     'average_score' => $averageScore,
    //                 ];
    //             }
    //         }

    //         // Sort by total score in descending order
    //         usort($cumulativeData, function ($a, $b) {
    //             return $b['total_score'] <=> $a['total_score'];
    //         });

    //         // Group students by their scores
    //         $scoreGroups = [];
    //         foreach ($cumulativeData as $data) {
    //             $score = $data['total_score'];
    //             if (!isset($scoreGroups[$score])) {
    //                 $scoreGroups[$score] = [];
    //             }
    //             $scoreGroups[$score][] = $data;
    //         }

    //         // Assign positions
    //         $position = 1;
    //         foreach ($scoreGroups as $score => $students) {
    //             $positionText = $this->ordinal($position);
    //             foreach ($students as $student) {
    //                 Result::updateOrCreate(
    //                     [
    //                         'student_id' => $student['student_id'],
    //                         'term_id' => $termId,
    //                         'session_year_id' => $sessionYearId,
    //                     ],
    //                     [
    //                         'classroom_id' => $classroomId,
    //                         'cumulative' => $student['total_score'],
    //                         'c_average' => $student['average_score'],
    //                         'c_position' => $positionText,
    //                     ]
    //                 );
    //             }
    //             $position += count($students);
    //         }
    //     }

    //     return back()->with('success', 'Results calculated successfully with batch-based positions.');
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

        // Get students in the classroom grouped by batch
        $studentsByBatch = Student::where('classroom_id', $classroomId)
            ->get()
            ->groupBy('batch_id');

        foreach ($studentsByBatch as $batchId => $students) {
            $cumulativeData = [];

            foreach ($students as $student) {
                // TERM TOTAL & AVERAGE
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
                        'c_average' => null,
                        'cumulative' => null,
                        'c_position' => null,
                    ]
                );

                // CUMULATIVE (across all terms)
                $allScores = StudentScore::where('student_id', $student->id)
                    ->where('session_year_id', $sessionYearId)
                    ->get();

                $cumulativeTotal = $allScores->sum('total_score');
                $subjectCount = $allScores->count();
                $cumulativeAverage = $subjectCount > 0 ? round($cumulativeTotal / $subjectCount, 2) : 0;

                $cumulativeData[] = [
                    'student_id' => $student->id,
                    'cumulative' => $cumulativeTotal,
                ];

                Result::where('student_id', $student->id)
                    ->where('classroom_id', $classroomId)
                    ->where('session_year_id', $sessionYearId)
                    ->update([
                        'c_average' => $cumulativeAverage,
                        'cumulative' => $cumulativeTotal,
                        'c_position' => null,
                    ]);
            }

            // 🔢 ASSIGN TERM POSITION for this batch
            $results = Result::whereIn('student_id', $students->pluck('id'))
                ->where('classroom_id', $classroomId)
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

            // 🔢 ASSIGN CUMULATIVE POSITION for this batch
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

                Result::where('student_id', $studentId)
                    ->where('classroom_id', $classroomId)
                    ->where('session_year_id', $sessionYearId)
                    ->orderByDesc('term_id')
                    ->first()?->update([
                        'c_position' => $cOrdinal
                    ]);

                $lastCumulative = $cumulative;
                $cPos++;
            }
        }

        return back()->with('success', 'Results calculated and batch-wise positions assigned successfully.');
    }



    private function calculateGrade($average)
    {
        if ($average >= 70) return 'A';
        if ($average >= 60) return 'B';
        if ($average >= 50) return 'C';
        if ($average >= 45) return 'D';
        return 'F';
    }


    private function ordinal($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        return ((($number % 100) >= 11) && (($number % 100) <= 13))
            ? $number . 'th'
            : $number . $ends[$number % 10];
    }




    public function viewStudentResult(Request $request)
    {


        $request->validate([
            'term_id' => 'required|exists:terms,id',
            'session_year_id' => 'required|exists:session_years,id',
            'scratch_card_code' => Auth::check() ? ['nullable', 'alpha_num'] : ['required', 'alpha_num'],
        ]);

        $studentId = $request->student_id;
        $termId = $request->term_id;
        $sessionYearId = $request->session_year_id;
        $inputCode = $request->scratch_card_code;
        $classroomId = $request->classroom_id; // Get classroom_id if provided


        if (!Auth::check()) {
            if (session()->has('viewed_result')) {
                session()->forget('viewed_result');
                return redirect('/results/select');
            }

            // Mark this session as having viewed the result page
            session()->put('viewed_result', true);
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
        }


        $student = Student::with('classroom')->findOrFail($studentId);

        // Build result query
        $resultQuery = Result::with('term', 'sessionYear', 'classroom')
            ->where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('session_year_id', $sessionYearId);

        // If classroom_id is provided, filter by it
        if ($classroomId) {
            $resultQuery->where('classroom_id', $classroomId);
        }

        $result = $resultQuery->first();

        // Build scores query
        $scoresQuery = StudentScore::with('subject')
            ->where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('session_year_id', $sessionYearId);

        // If classroom_id is provided, we need to filter scores by subjects that belong to this classroom
        if ($classroomId) {
            $classroomSubjectIds = \App\Models\ClassSubjectTerm::where('classroom_id', $classroomId)
                ->where('term_id', $termId)
                ->where('session_year_id', $sessionYearId)
                ->pluck('subject_id');
            
            $scoresQuery->whereIn('subject_id', $classroomSubjectIds);
        }

        $scores = $scoresQuery->orderBy('subject_id')->get();

        $scoresData = $scores->map(function ($score) {
            $caTotal = (int) ($score->ca1_score ?? 0) + (int) ($score->ca2_score ?? 0);

            return [
                'subject_name' => $score->subject->name ?? 'N/A',
                'ca' => $caTotal > 0 ? $caTotal : null,
                'exam' => $score->exam_score,
                'total_score' => $score->total_score,
                'grade' => $score->grade,
                'remark' => $score->remark,
            ];
        })->values();

        $groupedScores = null;
        $totalSum = null;
        $averageScore = null;
        $cummulativePosition = null;

        if ($termId == 3) {
            $terms = [1 => 'first', 2 => 'second', 3 => 'third'];
            $groupedScores = [];

            foreach ($terms as $termIdKey => $termLabel) {
                $termScoresQuery = StudentScore::with('subject')
                    ->where('student_id', $studentId)
                    ->where('term_id', $termIdKey)
                    ->where('session_year_id', $sessionYearId);

                // If classroom_id is provided, filter by classroom subjects
                if ($classroomId) {
                    $classroomSubjectIds = \App\Models\ClassSubjectTerm::where('classroom_id', $classroomId)
                        ->where('term_id', $termIdKey)
                        ->where('session_year_id', $sessionYearId)
                        ->pluck('subject_id');
                    
                    $termScoresQuery->whereIn('subject_id', $classroomSubjectIds);
                }

                $termScores = $termScoresQuery->get();

                foreach ($termScores as $score) {
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
        }


        $totalSum = optional($result)->cumulative;

        $averageScore = optional($result)->c_average;
        $cummulativePosition = optional($result)->c_position;


        if (!Auth::check()) {
            $code->uses_left--;
            $code->save();
        }

        // $totalStudents = $student->classroom->students()->count();
        $totalStudents = $student->classroom->students()
            ->where('batch_id', $student->batch_id)
            ->count();
        $batchName = optional($student->batch)->name;

        $selectedClassroomId = $classroomId ?? optional($result)->classroom_id ?? $student->classroom_id;
        $classSubjectCount = ClassSubjectTerm::where('classroom_id', $selectedClassroomId)
            ->where('term_id', $termId)
            ->where('session_year_id', $sessionYearId)
            ->count();

        $assessedSubjects = $scoresData->filter(fn($score) => !is_null($score['total_score']))->count();

        $sessionName = $result?->sessionYear?->name ?? 'N/A';
        $termName = $result?->term?->name ?? 'N/A';
        $className = $result?->classroom?->name ?? $student->classroom->name ?? 'N/A';

        $resultData = [
            'session' => [
                'session_name' => $sessionName,
                'term_name' => $termName,
                'class_name' => $className,
            ],
            'performance' => [
                'grade' => optional($result)->grade ?? 'N/A',
                'average_score' => number_format((float) optional($result)->average, 2),
                'assessed_subjects' => $assessedSubjects,
                'total_subjects' => max($classSubjectCount, $scoresData->count()),
                'rank' => optional($result)->position ?? 'N/A',
            ],
            'scores' => $scoresData->all(),
            'behavioral_traits' => [],
            'attendance' => [
                'school_opened' => null,
                'times_present' => null,
                'percentage' => null,
                'punctuality' => null,
            ],
            'comments' => [
                'teacher' => optional($result)->teacher_remark,
                'head_teacher' => optional($result)->principal_remark,
            ],
        ];

        $generatedAt = now()->format('F j, Y g:i A');

        return view('results.student', compact(
            'student',
            'result',
            'scores',
            'termId',
            'sessionYearId',
            'groupedScores',
            'totalSum',
            'averageScore',
            'cummulativePosition',
            'totalStudents',
            'batchName',
            'resultData',
            'generatedAt'
        ));
    }
}
