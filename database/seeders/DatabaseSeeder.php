<?php

namespace Database\Seeders;

use App\Models\Batch;
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





 
    public function run()
    {
        // Step 1: Seed roles and create admin user
        $this->call([
            RolePermissionSeeder::class,
            // UserSeeder::class,
            BatchSeeder::class,
        ]);

        // Step 2: Create terms
        $terms = collect(['1st', '2nd', '3rd'])->map(
            fn($term) => Term::firstOrCreate(['name' => $term])
        );

        // Step 3: Create session years
        $sessionYears = SessionYear::factory(3)->create();

        // Step 4: Create classrooms with promotion_order
        $classroomNames = ['JSS1', 'JSS2', 'JSS3', 'SSS1', 'SSS2', 'SSS3'];
        $classrooms = collect($classroomNames)->map(function ($name, $index) {
            return Classroom::firstOrCreate(
                ['name' => $name],
                ['promotion_order' => $index + 1]
            );
        });

        // Step 5: Create subjects
        // $subjects = Subject::factory(15)->create();

        // // Step 6: Create students with batches
        // $classroomStudents = [];
        // foreach ($classrooms as $classroom) {
        //     $batches = Batch::all(); // Get all predefined batches
        //     $studentsPerBatch = 5;
            
        //     foreach ($batches as $batch) {
        //         $classroomStudents[$classroom->id][] = Student::factory($studentsPerBatch)->create([
        //             'classroom_id' => $classroom->id,
        //             'batch_id' => $batch->id
        //         ]);
        //     }
        // }

        // // Step 7: Create class subject terms
        // foreach ($sessionYears as $sessionYear) {
        //     foreach ($terms as $term) {
        //         foreach ($classrooms as $classroom) {
        //             foreach ($subjects as $subject) {
        //                 ClassSubjectTerm::firstOrCreate([
        //                     'classroom_id' => $classroom->id,
        //                     'subject_id' => $subject->id,
        //                     'term_id' => $term->id,
        //                     'session_year_id' => $sessionYear->id
        //                 ]);
        //             }
        //         }
        //     }
        // }

        // // Step 8: Generate sample scores and results
        // foreach ($sessionYears as $sessionYear) {
        //     foreach ($terms as $term) {
        //         foreach ($classrooms as $classroom) {
        //             foreach ($classroomStudents[$classroom->id] as $students) {
        //                 foreach ($students as $student) {
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
    }
}
