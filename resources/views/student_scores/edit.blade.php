@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Student Score</h1>

    <form action="{{ route('student_scores.update', $studentScore->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <!-- Student -->
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                <select name="student_id" id="student_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $student->id == $studentScore->student_id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject -->
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="subject_id" id="subject_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $subject->id == $studentScore->subject_id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- CA Score -->
            <div>
                <label for="ca_score" class="block text-sm font-medium text-gray-700">CA Score</label>
                <input type="number" name="ca_score" id="ca_score" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="{{ $studentScore->ca_score }}" required>
            </div>

            <!-- Exam Score -->
            <div>
                <label for="exam_score" class="block text-sm font-medium text-gray-700">Exam Score</label>
                <input type="number" name="exam_score" id="exam_score" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="{{ $studentScore->exam_score }}" required>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn">
                Update Score
            </button>
        </div>
    </form>
</div>
@endsection
