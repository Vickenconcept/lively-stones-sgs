@extends('layouts.app')

@section('content')

    {{-- <form method="GET" action="{{ route('results.student', $student->id) }}" class="mb-4">
        <div class="flex space-x-4">
            <div>
                <label for="term_id">Term:</label>
                <select name="term_id" id="term_id" class="border rounded p-1">
                    @foreach (\App\Models\Term::all() as $term)
                        <option value="{{ $term->id }}" {{ request('term_id') == $term->id ? 'selected' : '' }}>
                            {{ $term->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="session_year_id">Session Year:</label>
                <select name="session_year_id" id="session_year_id" class="border rounded p-1">
                    @foreach (\App\Models\SessionYear::all() as $session)
                        <option value="{{ $session->id }}"
                            {{ request('session_year_id') == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filter</button>
            </div>
        </div>
    </form> --}}



    {{-- <div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-2">Results for {{ $student->name }}</h2>
    <p class="mb-4"><strong>Classroom:</strong> {{ $student->classroom->name ?? 'N/A' }}</p>

    <h3 class="text-xl font-semibold mt-6 mb-2">Subject Scores</h3>
    @if ($scores->count())
        <table class="w-full border border-gray-300 mb-6 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Session Year</th>
                    <th class="p-2 border">Term</th>
                    <th class="p-2 border">Subject</th>
                    <th class="p-2 border">CA1 Score</th>
                    <th class="p-2 border">CA2 Score</th>
                    <th class="p-2 border">Exam Score</th>
                    <th class="p-2 border">Total Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($scores as $score)
                    <tr>
                        <td class="p-2 border">{{ $score->sessionYear->name ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $score->term->name ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $score->subject->name ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $score->ca1_score }}</td>
                        <td class="p-2 border">{{ $score->ca2_score }}</td>
                        <td class="p-2 border">{{ $score->exam_score }}</td>
                        <td class="p-2 border">{{ $score->total_score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No subject scores available for this student.</p>
    @endif

    <h3 class="text-xl font-semibold mt-6 mb-2">Summary Results</h3>
    @if ($results->count())
        <table class="w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Session Year</th>
                    <th class="p-2 border">Term</th>
                    <th class="p-2 border">Total Score</th>
                    <th class="p-2 border">Average</th>
                    <th class="p-2 border">Grade</th>
                    <th class="p-2 border">Position</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td class="p-2 border">{{ $result->sessionYear->name ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $result->term->name ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $result->total_score }}</td>
                        <td class="p-2 border">{{ $result->average }}</td>
                        <td class="p-2 border">{{ $result->grade }}</td>
                        <td class="p-2 border">{{ $result->position }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No summary results available for this student.</p>
    @endif
</div> --}}

    <h2 class="text-2xl font-bold mb-2">Results for {{ $student->name }}</h2>
    <p class="mb-4"><strong>Classroom:</strong> {{ $student->classroom->name ?? 'N/A' }}</p>


    <h2 class="text-xl font-bold">Term Result</h2>
    @if ($result)
        <p>Total Score: {{ $result->total_score }}</p>
        <p>Average: {{ $result->average }}</p>
        <p>Grade: {{ $result->grade }}</p>
        <p>Position: {{ $result->position }}</p>
        <p>Session Year: {{ $result->sessionYear->name ?? 'N/A' }}</p>
        <p>Term: {{ $result->term->name ?? 'N/A' }}</p>
    @else
        <p>No result found for this term and session.</p>
    @endif

   

    <h3 class="text-xl font-semibold mt-6 mb-2">Subject Scores</h3>
    @if ($scores->count())
        <table class="w-full border border-gray-300 mb-6 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Subject</th>
                    <th class="p-2 border">CA1 Score</th>
                    <th class="p-2 border">CA2 Score</th>
                    <th class="p-2 border">Exam Score</th>
                    <th class="p-2 border">Total Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($scores as $score)
                    <tr>
                        <td class="p-2 border">{{ $score->subject->name ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $score->ca1_score }}</td>
                        <td class="p-2 border">{{ $score->ca2_score }}</td>
                        <td class="p-2 border">{{ $score->exam_score }}</td>
                        <td class="p-2 border">{{ $score->total_score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No subject scores available for this student.</p>
    @endif

@endsection
