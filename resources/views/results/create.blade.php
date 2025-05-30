@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Add New Result</h1>

    <form action="{{ route('results.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <!-- Student Selection -->
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                <select name="student_id" id="student_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject Selection -->
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="subject_id" id="subject_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Score -->
            <div>
                <label for="score" class="block text-sm font-medium text-gray-700">Score</label>
                <input type="number" name="score" id="score" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="{{ old('score') }}" required>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn">
                Save Result
            </button>
        </div>
    </form>
</div>
@endsection
