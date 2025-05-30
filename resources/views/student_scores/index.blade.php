@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Student Scores</h1>

    <!-- Add New Score Button -->
    <div class="mb-4 text-right">
        <a href="{{ route('student_scores.create') }}" class="btn">
            Add New Score
        </a>
    </div>

    <!-- Student Scores Table -->
    <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Student Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Subject
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        CA Score
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Exam Score
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Total Score
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($studentScores as $score)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $score->student->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $score->subject->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $score->ca_score }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $score->exam_score }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $score->total_score }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('student_scores.edit', $score->id) }}" class="text-blue-600 hover:text-blue-900">
                                Edit
                            </a>
                            <form action="{{ route('student_scores.destroy', $score->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $studentScores->links() }}
    </div>
</div>
@endsection
