<?php

namespace App\Http\Controllers;

use App\Models\SessionYear;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\Request;

class StudentScoreController extends Controller
{
    public function index() {
        return view('student_scores.index', ['studentScores' => StudentScore::with('student', 'subject')->paginate(10)]);
    }

    public function create() {
        return view('student_scores.create', [
            'students' => Student::all(),
            'subjects' => Subject::all(),
            'terms' => Term::all(),
            'sessions' => SessionYear::all()
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'student_id' => 'required',
            'subject_id' => 'required',
            'term_id' => 'required',
            'session_year_id' => 'required',
            'ca_score' => 'required|numeric',
            'exam_score' => 'required|numeric'
        ]);
        $data['total_score'] = $data['ca_score'] + $data['exam_score'];
        StudentScore::updateOrCreate(
            array_slice($data, 0, 4), // identify by student/subject/term/session
            $data
        );
        return redirect()->route('student_scores.index');
    }
}