@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Result</h1>

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

    <!-- Form to Edit Result -->
    <form action="{{ route('results.update', $result->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <!-- Student Selection -->
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                <select name="student_id" id="student_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $result->student_id == $student->id ? 'selected' : '' }}>
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
                        <option value="{{ $subject->id }}" {{ $result->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Score -->
            <div>
                <label for="score" class="block text-sm font-medium text-gray-700">Score</label>
                <input type="number" name="score" id="score" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="{{ old('score', $result->score) }}" required>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Result
            </button>
        </div>
    </form>
</div>
@endsection
