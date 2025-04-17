<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassSubjectTerm;
use App\Models\SessionYear;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\Request;

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
        $students = $classSubjectTerm->classroom->students;

        $existingScores = \App\Models\StudentScore::where('subject_id', $classSubjectTerm->subject_id)
            ->where('term_id', $classSubjectTerm->term_id)
            ->where('session_year_id', $classSubjectTerm->session_year_id)
            ->get()
            ->keyBy('student_id');

        return view('class_subject_terms.upload_score', compact('classSubjectTerm', 'students', 'existingScores'));
    }

    public function uploadScore(Request $request, ClassSubjectTerm $classSubjectTerm)
    {
        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*.ca1' => 'nullable|numeric|min:0|max:100',
            'scores.*.ca2' => 'nullable|numeric|min:0|max:100',
            'scores.*.exam' => 'nullable|numeric|min:0|max:100',
        ]);


        foreach ($validated['scores'] as $studentId => $scoreData) {
            $ca1 = $scoreData['ca1'] ?? 0;
            $ca2 = $scoreData['ca2'] ?? 0;
            $exam = $scoreData['exam'] ?? 0;
            $total = $ca1 + $ca2 + $exam;

            StudentScore::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $classSubjectTerm->subject_id,
                    'term_id' => $classSubjectTerm->term_id,
                    'session_year_id' => $classSubjectTerm->session_year_id,
                ],
                [
                    'ca1_score' => $scoreData['ca1'] ?? 0,
                    'ca2_score' => $scoreData['ca2'] ?? 0,
                    'exam_score' => $scoreData['exam'] ?? 0,
                    'total_score' => $total,
                ]
            );
        }

        return back()
            ->with('success', 'Scores uploaded successfully!');
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
