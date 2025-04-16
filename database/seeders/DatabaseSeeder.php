<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  

    public function run()
    {
        // Create fixed terms (1st, 2nd, 3rd) only once
        $terms = collect(['1st', '2nd', '3rd'])->map(function ($termName) {
            return \App\Models\Term::firstOrCreate(['name' => $termName]);
        });

        \App\Models\Classroom::factory(2)->create()->each(function ($classroom) use ($terms) {
            $students = \App\Models\Student::factory(5)->create([
                'classroom_id' => $classroom->id
            ]);

            $subjects = \App\Models\Subject::factory(10)->create();
            $sessionYears = \App\Models\SessionYear::factory(1)->create();

            foreach ($sessionYears as $sessionYear) {
                foreach ($terms as $term) {
                    foreach ($subjects as $subject) {
                        \App\Models\ClassSubjectTerm::create([
                            'classroom_id' => $classroom->id,
                            'subject_id' => $subject->id,
                            'term_id' => $term->id,
                            'session_year_id' => $sessionYear->id,
                        ]);
                    }

                    foreach ($students as $student) {
                        foreach ($subjects as $subject) {
                            $ca1 = rand(1, 15);
                            $ca2 = rand(1, 15);
                            $exam = rand(20, 70);
                            $total = $ca1 + $ca2 + $exam;

                            \App\Models\StudentScore::create([
                                'student_id' => $student->id,
                                'subject_id' => $subject->id,
                                'term_id' => $term->id,
                                'session_year_id' => $sessionYear->id,
                                'ca1_score' => $ca1,
                                'ca2_score' => $ca2,
                                'exam_score' => $exam,
                                'total_score' => $total,
                            ]);

                            \App\Models\Result::create([
                                'student_id' => $student->id,
                                'classroom_id' => $classroom->id,
                                'term_id' => $term->id,
                                'session_year_id' => $sessionYear->id,
                                'subject_id' => $subject->id,
                                'total_score' => $total,
                                'average' => $total,
                                'grade' => $total >= 70 ? 'A' : ($total >= 60 ? 'B' : ($total >= 50 ? 'C' : 'F')),
                                'position' => null,
                            ]);
                        }
                    }
                }
            }
        });
    }
}
