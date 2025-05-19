<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->constrained();
            // $table->foreignId('subject_id')->constrained();
            $table->foreignId('term_id')->constrained();
            $table->foreignId('session_year_id')->constrained()->onDelete('cascade');
            $table->integer('ca1_score')->default(0);
            $table->integer('ca2_score')->default(0);
            $table->string('grade')->nullable();
            $table->string('position')->nullable();
            $table->string('remark')->nullable();
            $table->integer('exam_score')->default(0);
            $table->integer('total_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_scores');
    }
};
