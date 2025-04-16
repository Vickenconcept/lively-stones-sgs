@extends('layouts.app')

@section('content')
    <h2>Upload Scores for {{ $classSubjectTerm->subject->name }} - {{ $classSubjectTerm->term->name }}
        ({{ $classSubjectTerm->sessionYear->name }})</h2>

    <form method="POST" action="{{ route('class_subject_terms.upload_score', $classSubjectTerm) }}">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>CA 1</th>
                    <th>CA 2</th>
                    <th>Exam</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    @php
                        $score = $existingScores[$student->id] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>
                            <input type="number" name="scores[{{ $student->id }}][ca1]" min="0" max="100"
                                step="0.01" class="border p-1 rounded w-24"
                                value="{{ old("scores.{$student->id}.ca1", $score->ca1_score ?? '') }}" />
                        </td>
                        <td>
                            <input type="number" name="scores[{{ $student->id }}][ca2]" min="0" max="100"
                                step="0.01" class="border p-1 rounded w-24"
                                value="{{ old("scores.{$student->id}.ca2", $score->ca2_score ?? '') }}" />
                        </td>
                        <td>
                            <input type="number" name="scores[{{ $student->id }}][exam]" min="0" max="100"
                                step="0.01" class="border p-1 rounded w-24"
                                value="{{ old("scores.{$student->id}.exam", $score->exam_score ?? '') }}" />
                        </td>
                        <td>
                            <input type="number" name="scores[{{ $student->id }}][exam]" min="0" max="100"
                                step="0.01" class="border p-1 rounded w-24 bg-gray-300"
                                value="{{ old("scores.{$student->id}.total", $score->total_score ?? '') }}" readonly disabled />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Submit Scores</button>
    </form>
@endsection
