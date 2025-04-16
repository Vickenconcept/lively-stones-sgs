@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-6 text-center">View Student Result</h2>

       <x-session-msg />
        <form action="{{ route('results.student') }}" method="GET">
            @csrf

            <div class="mb-4">
                <label for="scratch_card_code" class="block text-gray-700 font-semibold">scratch_card_code</label>
                <input type="text" name="scratch_card_code" id="scratch_card_code"
                    class="w-full border-gray-300 rounded mt-1" required>
            </div>
            <div class="mb-4">
                <label for="student_id" class="block text-gray-700 font-semibold">Student ID</label>
                <input type="text" name="student_id" id="student_id" class="w-full border-gray-300 rounded mt-1"
                    required>
            </div>

            <div class="mb-4">
                <label for="term_id" class="block text-gray-700 font-semibold">Term</label>
                <select name="term_id" id="term_id" class="w-full border-gray-300 rounded mt-1" required>
                    <option value="">-- Select Term --</option>
                    @foreach ($terms as $term)
                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="session_year_id" class="block text-gray-700 font-semibold">Session Year</label>
                <select name="session_year_id" id="session_year_id" class="w-full border-gray-300 rounded mt-1" required>
                    <option value="">-- Select Session --</option>
                    @foreach ($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                View Result
            </button>
        </form>
    </div>
@endsection
