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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('term_id')->constrained();
            $table->foreignId('session_year_id')->constrained()->onDelete('cascade');
            $table->integer('total_score');
            $table->float('average');
            $table->string('grade');
            $table->string('position')->nullable();
            $table->string('c_avarage')->nullable();
            $table->integer('cumulative')->nullable();
            $table->string('c_position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
