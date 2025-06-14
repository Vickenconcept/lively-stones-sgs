<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassSubjectTerm;
use App\Models\SessionYear;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ClassSubjectTermController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->class_id;
        $termId = $request->term_id;
        $sessionYearId = $request->session_year_id;

        $query = ClassSubjectTerm::with(['classroom', 'subject', 'term', 'sessionYear']);

        if ($classId) {
            $query->where('classroom_id', $classId);
        }

        if ($termId) {
            $query->where('term_id', $termId);
        }

        if ($sessionYearId) {
            $query->where('session_year_id', $sessionYearId);
        }

        $classSubjectTerms = $query->paginate(15);

        return view('class_subject_terms.index', compact('classSubjectTerms'));
    }

    public function create()
    {
        return view('class_subject_terms.create', [
            'classrooms' => Classroom::all(),
            'subjects' => Subject::all(),
            'terms' => Term::all(),
            'sessionYears' => SessionYear::all()
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom_id' => 'required',
            'subject_id' => 'required',
            'term_id' => 'required',
            'session_year_id' => 'required',
        ]);
        ClassSubjectTerm::create($data);
        return redirect()->route('class_subject_terms.index');
    }

    public function edit(ClassSubjectTerm $classSubjectTerm)
    {

        return view('class_subject_terms.edit', [
            'classSubjectTerm' => $classSubjectTerm,
            'classrooms' => Classroom::all(),
            'subjects' => Subject::all(),
            'terms' => Term::all(),
            'sessionYears' => SessionYear::all()
        ]);
    }

    public function update(Request $request, ClassSubjectTerm $classSubjectTerm)
    {
        $classSubjectTerm->update($request->all());
        return redirect()->route('class_subject_terms.index');
    }



    public function uploadScoreForm(ClassSubjectTerm $classSubjectTerm)
    {
        $batches = Batch::all();
        $selectedBatchId = request('batch_id');

        $query = $classSubjectTerm->classroom->students();

        if ($selectedBatchId) {
            $query->where('batch_id', $selectedBatchId);
        }

        $students = $query->get();

        $existingScores = \App\Models\StudentScore::where('subject_id', $classSubjectTerm->subject_id)
            ->where('term_id', $classSubjectTerm->term_id)
            ->where('session_year_id', $classSubjectTerm->session_year_id)
            ->get()
            ->keyBy('student_id');

        $sampleScore = $existingScores->first();

        $editor = $sampleScore ? \App\Models\User::find($sampleScore->edited_by) : null;

        return view('class_subject_terms.upload_score', compact('classSubjectTerm', 'students', 'existingScores', 'editor', 'batches', 'selectedBatchId'));
    }


    public function uploadScore(Request $request, ClassSubjectTerm $classSubjectTerm)
    {
        $validated = $request->validate([
            'scores'         => 'required|array',
            'scores.*.ca1'   => 'nullable|numeric|min:0|max:100',
            'scores.*.ca2'   => 'nullable|numeric|min:0|max:100',
            'scores.*.exam'  => 'nullable|numeric|min:0|max:100',
            'batch_id'       => 'nullable|exists:batches,id',
        ]);

        foreach ($validated['scores'] as $studentId => $s) {
            $ca1   = $s['ca1']  ?? 0;
            $ca2   = $s['ca2']  ?? 0;
            $exam  = $s['exam'] ?? 0;
            $total = $ca1 + $ca2 + $exam;

            $grade = match (true) {
                $total >= 70 => 'A',
                $total >= 60 => 'B',
                $total >= 50 => 'C',
                $total >= 45 => 'D',
                $total >= 40 => 'E',
                default      => 'F',
            };

            $remark = match ($grade) {
                'A' => 'Excellent',
                'B' => 'Very Good',
                'C' => 'Good',
                'D' => 'Fair',
                'E' => 'Poor',
                default => 'Fail',
            };

            StudentScore::updateOrCreate(
                [
                    'student_id'      => $studentId,
                    'subject_id'      => $classSubjectTerm->subject_id,
                    'term_id'         => $classSubjectTerm->term_id,
                    'session_year_id' => $classSubjectTerm->session_year_id,
                ],
                [
                    'ca1_score'       => $ca1,
                    'ca2_score'       => $ca2,
                    'exam_score'      => $exam,
                    'total_score'     => $total,
                    'grade'           => $grade,
                    'remark'          => $remark,
                    'edited_by'       => auth()->id(),
                ]
            );
        }

        // 🔸 Get students in the specified batch (if batch_id is given)
        $batchId = $validated['batch_id'] ?? null;

        $studentIds = $classSubjectTerm->classroom
            ->students()
            ->when($batchId, function ($query) use ($batchId) {
                $query->where('batch_id', $batchId);
            })
            ->pluck('id');

        $scores = StudentScore::where([
            'subject_id'      => $classSubjectTerm->subject_id,
            'term_id'         => $classSubjectTerm->term_id,
            'session_year_id' => $classSubjectTerm->session_year_id,
        ])
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('total_score')
            ->get(['id', 'total_score']);

        // 🔸 Rank students in the selected batch
        $rank = 0;
        $place = 0;
        $prev = null;

        DB::transaction(function () use ($scores, &$rank, &$place, &$prev) {
            foreach ($scores as $score) {
                $place++;

                if ($score->total_score !== $prev) {
                    $rank = $place;
                    $prev = $score->total_score;
                }

                $score->update(['position' => $this->ordinal($rank)]);
            }
        });

        return back()->with('success', 'Scores uploaded, grades/remarks set, positions updated.');
    }





    // public function uploadScoresExcel(Request $request, ClassSubjectTerm $classSubjectTerm)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt',
    //         'batch_id' => 'nullable|exists:batches,id'
    //     ]);

    //     // Read the entire file as an array, first sheet only
    //     $rows = Excel::toArray([], $request->file('excel_file'))[0];

    //     // Skip the header row (assuming first row is headers)
    //     foreach (array_slice($rows, 1) as $row) {
    //         $registrationNumber = $row[0] ?? null;
    //         $ca1 = isset($row[1]) ? floatval($row[1]) : 0;
    //         $ca2 = isset($row[2]) ? floatval($row[2]) : 0;
    //         $exam = isset($row[3]) ? floatval($row[3]) : 0;

    //         if (!$registrationNumber) {
    //             continue; // Skip empty or malformed rows
    //         }

    //         // Lookup student by registration_number in this classroom
    //         $query = $classSubjectTerm->classroom->students()->where('registration_number', $registrationNumber);

    //         if ($request->batch_id) {
    //             $query->where('batch_id', $request->batch_id);
    //         }

    //         $student = $query->first();

    //         if (!$student) {
    //             // Optionally skip or log unknown registration numbers
    //             continue;
    //         }

    //         $total = $ca1 + $ca2 + $exam;

    //         $grade = match (true) {
    //             $total >= 70 => 'A',
    //             $total >= 60 => 'B',
    //             $total >= 50 => 'C',
    //             $total >= 45 => 'D',
    //             $total >= 40 => 'E',
    //             default      => 'F',
    //         };

    //         $remark = match ($grade) {
    //             'A' => 'Excellent',
    //             'B' => 'Very Good',
    //             'C' => 'Good',
    //             'D' => 'Fair',
    //             'E' => 'Poor',
    //             default => 'Fail',
    //         };

    //         StudentScore::updateOrCreate(
    //             [
    //                 'student_id'      => $student->id,
    //                 'subject_id'      => $classSubjectTerm->subject_id,
    //                 'term_id'         => $classSubjectTerm->term_id,
    //                 'session_year_id' => $classSubjectTerm->session_year_id,
    //             ],
    //             [
    //                 'ca1_score'   => $ca1,
    //                 'ca2_score'   => $ca2,
    //                 'exam_score'  => $exam,
    //                 'total_score' => $total,
    //                 'grade'       => $grade,
    //                 'remark'      => $remark,
    //                 'edited_by'   => auth()->id(),
    //             ]
    //         );
    //     }

    //     // Update positions as before
    //     $query = $classSubjectTerm->classroom->students();

    //     if ($request->batch_id) {
    //         $query->where('batch_id', $request->batch_id);
    //     }

    //     $studentIds = $query->pluck('id');

    //     $scores = StudentScore::where([
    //         'subject_id'      => $classSubjectTerm->subject_id,
    //         'term_id'         => $classSubjectTerm->term_id,
    //         'session_year_id' => $classSubjectTerm->session_year_id,
    //     ])
    //         ->whereIn('student_id', $studentIds)
    //         ->orderByDesc('total_score')
    //         ->get(['id', 'total_score']);

    //     $rank = 0;
    //     $place = 0;
    //     $prev = null;

    //     DB::transaction(function () use ($scores, &$rank, &$place, &$prev) {
    //         foreach ($scores as $score) {
    //             $place++;

    //             if ($score->total_score !== $prev) {
    //                 $rank = $place;
    //                 $prev = $score->total_score;
    //             }

    //             $score->update(['position' => $this->ordinal($rank)]);
    //         }
    //     });

    //     return back()->with('success', 'Scores uploaded successfully via Excel/CSV using registration numbers.');
    // }

    public function uploadScoresExcel(Request $request, ClassSubjectTerm $classSubjectTerm)
    {
        $validated = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt',
            'batch_id'   => 'nullable|exists:batches,id',
        ]);

        $batchId = $validated['batch_id'] ?? null;

        // Read the first sheet
        $rows = Excel::toArray([], $request->file('excel_file'))[0];

        foreach (array_slice($rows, 1) as $row) {
            $registrationNumber = $row[0] ?? null;
            $ca1 = isset($row[1]) ? floatval($row[1]) : 0;
            $ca2 = isset($row[2]) ? floatval($row[2]) : 0;
            $exam = isset($row[3]) ? floatval($row[3]) : 0;

            if (!$registrationNumber) {
                continue;
            }

            $studentQuery = $classSubjectTerm->classroom->students()
                ->where('registration_number', $registrationNumber);

            if ($batchId) {
                $studentQuery->wherePivot('batch_id', $batchId);
            }

            $student = $studentQuery->first();

            if (!$student) {
                continue;
            }

            $total = $ca1 + $ca2 + $exam;

            $grade = match (true) {
                $total >= 70 => 'A',
                $total >= 60 => 'B',
                $total >= 50 => 'C',
                $total >= 45 => 'D',
                $total >= 40 => 'E',
                default      => 'F',
            };

            $remark = match ($grade) {
                'A' => 'Excellent',
                'B' => 'Very Good',
                'C' => 'Good',
                'D' => 'Fair',
                'E' => 'Poor',
                default => 'Fail',
            };

            StudentScore::updateOrCreate(
                [
                    'student_id'      => $student->id,
                    'subject_id'      => $classSubjectTerm->subject_id,
                    'term_id'         => $classSubjectTerm->term_id,
                    'session_year_id' => $classSubjectTerm->session_year_id,
                ],
                [
                    'ca1_score'   => $ca1,
                    'ca2_score'   => $ca2,
                    'exam_score'  => $exam,
                    'total_score' => $total,
                    'grade'       => $grade,
                    'remark'      => $remark,
                    'edited_by'   => auth()->id(),
                ]
            );
        }

        // Get students in the classroom (and batch if specified)
        $studentIds = $classSubjectTerm->classroom->students()
            ->when($batchId, fn($query) => $query->wherePivot('batch_id', $batchId))
            ->pluck('id');

        $scores = StudentScore::where([
            'subject_id'      => $classSubjectTerm->subject_id,
            'term_id'         => $classSubjectTerm->term_id,
            'session_year_id' => $classSubjectTerm->session_year_id,
        ])
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('total_score')
            ->get(['id', 'total_score']);

        $rank = 0;
        $place = 0;
        $prev = null;

        DB::transaction(function () use ($scores, &$rank, &$place, &$prev) {
            foreach ($scores as $score) {
                $place++;

                if ($score->total_score !== $prev) {
                    $rank = $place;
                    $prev = $score->total_score;
                }

                $score->update(['position' => $this->ordinal($rank)]);
            }
        });

        return back()->with('success', 'Scores uploaded successfully via Excel and ranked based on batch.');
    }




    private function ordinal($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        return ((($number % 100) >= 11) && (($number % 100) <= 13))
            ? $number . 'th'
            : $number . $ends[$number % 10];
    }



    public function destroy(ClassSubjectTerm $ClassSubjectTerm)
    {
        StudentScore::where('subject_id', $ClassSubjectTerm->subject_id)
            ->where('term_id', $ClassSubjectTerm->term_id)
            ->where('session_year_id', $ClassSubjectTerm->session_year_id)
            ->delete();

        $ClassSubjectTerm->delete();

        return back();
    }
}
