@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Add New Score</h1>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to Create New Student Score -->
    <form action="{{ route('student_scores.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <!-- Student -->
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                <select name="student_id" id="student_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subject -->
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="subject_id" id="subject_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- CA Score -->
            <div>
                <label for="ca_score" class="block text-sm font-medium text-gray-700">CA Score</label>
                <input type="number" name="ca_score" id="ca_score" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <!-- Exam Score -->
            <div>
                <label for="exam_score" class="block text-sm font-medium text-gray-700">Exam Score</label>
                <input type="number" name="exam_score" id="exam_score" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Score
            </button>
        </div>
    </form>
</div>
@endsection
