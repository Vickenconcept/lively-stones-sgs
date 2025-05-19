<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ClassSubjectTerm;
use App\Models\Result;
use App\Models\SessionYear;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{





    // public function run()
    // {
    //     // Step 1: Create terms
    //     $terms = collect(['1st', '2nd', '3rd'])->map(fn($term) =>
    //         Term::firstOrCreate(['name' => $term])
    //     );

    //     // Step 2: Create session years
    //     $sessionYears = SessionYear::factory(5)->create();

    //     // Step 3: Create classrooms
    //     $classroomNames = ['JSS1', 'JSS2', 'JSS3', 'SSS1', 'SSS2', 'SSS3'];
    //     $classrooms = collect($classroomNames)->map(fn($name) =>
    //         Classroom::firstOrCreate(['name' => $name])
    //     );

    //     // Step 4: Create subjects
    //     $subjects = Subject::factory(10)->create();

    //     // Step 5: For each classroom, create 15 students ONLY ONCE
    //     $classroomStudents = [];
    //     foreach ($classrooms as $classroom) {
    //         $classroomStudents[$classroom->id] = Student::factory(15)->create([
    //             'classroom_id' => $classroom->id,
    //         ]);
    //     }

    //     // Step 6: For each session, term, and classroom, generate results
    //     foreach ($sessionYears as $sessionYear) {
    //         foreach ($terms as $term) {
    //             foreach ($classrooms as $classroom) {
    //                 // Assign subjects to this classroom for this session/term
    //                 foreach ($subjects as $subject) {
    //                     ClassSubjectTerm::firstOrCreate([
    //                         'classroom_id' => $classroom->id,
    //                         'subject_id' => $subject->id,
    //                         'term_id' => $term->id,
    //                         'session_year_id' => $sessionYear->id,
    //                     ]);
    //                 }

    //                 // Now assign results for the 15 students created earlier
    //                 foreach ($classroomStudents[$classroom->id] as $student) {
    //                     foreach ($subjects as $subject) {
    //                         $ca1 = rand(5, 15);
    //                         $ca2 = rand(5, 15);
    //                         $exam = rand(40, 70);
    //                         $total = $ca1 + $ca2 + $exam;

    //                         StudentScore::create([
    //                             'student_id' => $student->id,
    //                             'subject_id' => $subject->id,
    //                             'term_id' => $term->id,
    //                             'session_year_id' => $sessionYear->id,
    //                             'ca1_score' => $ca1,
    //                             'ca2_score' => $ca2,
    //                             'exam_score' => $exam,
    //                             'total_score' => $total,
    //                         ]);

    //                         Result::create([
    //                             'student_id' => $student->id,
    //                             'classroom_id' => $classroom->id,
    //                             'term_id' => $term->id,
    //                             'session_year_id' => $sessionYear->id,
    //                             'subject_id' => $subject->id,
    //                             'total_score' => $total,
    //                             'average' => $total,
    //                             'grade' => $total >= 70 ? 'A' : ($total >= 60 ? 'B' : ($total >= 50 ? 'C' : 'F')),
    //                             'position' => null,
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }
    public function run()
    {
        // Step 1: Create terms
        $terms = collect(['1st', '2nd', '3rd'])->map(
            fn($term) =>
            Term::firstOrCreate(['name' => $term])
        );

        // Step 2: Create session years
        $sessionYears = SessionYear::factory(3)->create();

        // Step 3: Create classrooms with promotion_order
        $classroomNames = ['JSS1', 'JSS2', 'JSS3', 'SSS1', 'SSS2', 'SSS3'];
        $classrooms = collect($classroomNames)->map(function ($name, $index) {
            return Classroom::firstOrCreate(
                ['name' => $name],
                ['promotion_order' => $index + 1]
            );
        });

        // Step 4: Create subjects
        $subjects = Subject::factory(15)->create();

        $classroomStudents = [];
        foreach ($classrooms as $classroom) {
            $classroomStudents[$classroom->id] = Student::factory(5)->create([
                'classroom_id' => $classroom->id,
            ]);
        }

        foreach ($sessionYears as $sessionYear) {
            foreach ($terms as $term) {
                foreach ($classrooms as $classroom) {
                    foreach ($subjects as $subject) {
                        ClassSubjectTerm::firstOrCreate([
                            'classroom_id' => $classroom->id,
                            'subject_id' => $subject->id,
                            'term_id' => $term->id,
                            'session_year_id' => $sessionYear->id,
                        ]);
                    }

                    $positionBuckets = [];
                    foreach ($classroomStudents[$classroom->id] as $student) {
                        foreach ($subjects as $subject) {
                            $ca1 = rand(5, 15);
                            $ca2 = rand(5, 15);
                            $exam = rand(40, 70);
                            $total = $ca1 + $ca2 + $exam;

                            $score = StudentScore::create([
                                'student_id' => $student->id,
                                'subject_id' => $subject->id,
                                'term_id' => $term->id,
                                'session_year_id' => $sessionYear->id,
                                'ca1_score' => $ca1,
                                'ca2_score' => $ca2,
                                'exam_score' => $exam,
                                'total_score' => $total,
                            ]);

                            $bucketKey = "{$classroom->id}-{$subject->id}";

                            $positionBuckets[$bucketKey][] = [
                                'id'    => $score->id,   // primary‑key of row just inserted
                                'total' => $total,
                            ];

                            Result::create([
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

                    /* -------------------------------------------
   2️⃣  PASS 2  – Rank, grade & remark
-------------------------------------------*/

                    foreach ($positionBuckets as $bucket) {

                        $sorted = collect($bucket)->sortByDesc('total')->values();

                        $rank = 0;
                        $prevTotal = null;
                        $place = 0;

                        foreach ($sorted as $row) {
                            $place++;
                            if ($row['total'] !== $prevTotal) {
                                $rank      = $place;
                                $prevTotal = $row['total'];
                            }

                            /* Grade table */
                            $grade = match (true) {
                                $row['total'] >= 70 => 'A',
                                $row['total'] >= 60 => 'B',
                                $row['total'] >= 50 => 'C',
                                $row['total'] >= 45 => 'D',
                                $row['total'] >= 40 => 'E',
                                default            => 'F',
                            };

                            /* Remark table */
                            $remark = match ($grade) {
                                'A' => 'Excellent',
                                'B' => 'Very Good',
                                'C' => 'Good',
                                'D' => 'Fair',
                                'E' => 'Poor',
                                default => 'Fail',
                            };

                            StudentScore::whereKey($row['id'])->update([
                                'grade'    => $grade,
                                'position' => $rank,
                                'remark'   => $remark,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
