<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Result;
use App\Models\SessionYear;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPromotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_promotion_preserves_historical_data()
    {
        // Create test data
        $class1 = Classroom::factory()->create(['name' => 'JSS1', 'promotion_order' => 1]);
        $class2 = Classroom::factory()->create(['name' => 'JSS2', 'promotion_order' => 2]);
        $batch = Batch::factory()->create();
        $term = Term::factory()->create();
        $sessionYear = SessionYear::factory()->create();
        $subject = Subject::factory()->create();

        // Create a student in class1
        $student = Student::factory()->create([
            'classroom_id' => $class1->id,
            'batch_id' => $batch->id,
        ]);

        // Add some scores and results for the student in class1
        $score = StudentScore::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'term_id' => $term->id,
            'session_year_id' => $sessionYear->id,
            'total_score' => 85,
        ]);

        $result = Result::factory()->create([
            'student_id' => $student->id,
            'classroom_id' => $class1->id,
            'term_id' => $term->id,
            'session_year_id' => $sessionYear->id,
            'total_score' => 85,
        ]);

        // Promote the student
        $response = $this->post('/students/promote', [
            'students' => [$student->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Refresh the student data
        $student->refresh();

        // Assert student is now in class2
        $this->assertEquals($class2->id, $student->classroom_id);

        // Assert historical data is preserved
        $this->assertDatabaseHas('student_scores', [
            'id' => $score->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'term_id' => $term->id,
            'session_year_id' => $sessionYear->id,
            'total_score' => 85,
        ]);

        $this->assertDatabaseHas('results', [
            'id' => $result->id,
            'student_id' => $student->id,
            'classroom_id' => $class1->id, // Should still be class1
            'term_id' => $term->id,
            'session_year_id' => $sessionYear->id,
            'total_score' => 85,
        ]);

        // Assert student has no results in the new class
        $this->assertDatabaseMissing('results', [
            'student_id' => $student->id,
            'classroom_id' => $class2->id,
        ]);
    }

    public function test_classroom_show_filters_data_correctly()
    {
        // Create test data
        $class1 = Classroom::factory()->create(['name' => 'JSS1']);
        $class2 = Classroom::factory()->create(['name' => 'JSS2']);
        $batch = Batch::factory()->create();
        $term = Term::factory()->create();
        $sessionYear = SessionYear::factory()->create();
        $subject = Subject::factory()->create();

        // Create a student who was in class1 but is now in class2
        $student = Student::factory()->create([
            'classroom_id' => $class2->id,
            'batch_id' => $batch->id,
        ]);

        // Add historical result from class1
        $oldResult = Result::factory()->create([
            'student_id' => $student->id,
            'classroom_id' => $class1->id,
            'term_id' => $term->id,
            'session_year_id' => $sessionYear->id,
        ]);

        // Add current result from class2
        $newResult = Result::factory()->create([
            'student_id' => $student->id,
            'classroom_id' => $class2->id,
            'term_id' => $term->id,
            'session_year_id' => $sessionYear->id,
        ]);

        // Test viewing class2 (current class)
        $response = $this->get("/classrooms/{$class2->id}?term_id={$term->id}&session_year_id={$sessionYear->id}");

        $response->assertStatus(200);
        $response->assertSee($student->name);

        // Test viewing class1 (old class) - should show student in old students section
        $response = $this->get("/classrooms/{$class1->id}?term_id={$term->id}&session_year_id={$sessionYear->id}");

        $response->assertStatus(200);
        $response->assertSee($student->name);
    }
} 