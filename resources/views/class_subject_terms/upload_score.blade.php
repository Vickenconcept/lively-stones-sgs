@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <div class="mb-6">
            <h2>Upload Scores for
                <span class="font-bold text-black">{{ $classSubjectTerm->subject->name }}</span> -
                <span class="font-bold text-black">{{ $classSubjectTerm->term->name }}
                    ({{ $classSubjectTerm->sessionYear->name }})</span>
            </h2>
        </div>

        <div class="mb-3">
            <p>Total students: <span class="text-bold px-2 py-0.5 px-2  text-sm rounded-full bg-slate-700 text-yellow-400">{{ count($students ?? 0) }}</span></p>
        </div>
        <form method="POST" action="{{ route('class_subject_terms.upload_score', $classSubjectTerm) }}">
            @csrf
            <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg p-6">
                <table class="divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-2 px-2">Student</th>
                            <th class="text-left py-2 px-2">CA (1)</th>
                            <th class="text-left py-2 px-2">CA (2)</th>
                            <th class="text-left py-2 px-2">Exam</th>
                            <th class="text-left py-2 px-2">Total</th>
                            <th class="text-left py-2 px-2">Grade</th>
                            <th class="text-left py-2 px-2">Position</th>
                            <th class="text-left py-2 px-2">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            @php
                                $score = $existingScores[$student->id] ?? null;
                            @endphp
                            <tr  >
                                <td class="py-0.5 px-2 border-b border-gray-300 pr-6 font-semibold">{{ $student->name }}</td>
                                <td class="py-0.5 px-2 border-b border-gray-300">
                                    <input type="number" name="scores[{{ $student->id }}][ca1]" min="0"
                                        max="100" step="0.01" class="border p-1 rounded w-24" placeholder="eg. 20"
                                        value="{{ old("scores.{$student->id}.ca1", $score->ca1_score ?? '') }}" />
                                </td>
                                <td class="py-0.5 px-2 border-b border-gray-300">
                                    <input type="number" name="scores[{{ $student->id }}][ca2]" min="0"
                                        max="100" step="0.01" class="border p-1 rounded w-24" placeholder="eg. 20"
                                        value="{{ old("scores.{$student->id}.ca2", $score->ca2_score ?? '') }}" />
                                </td>
                                <td class="py-0.5 px-2 border-b border-gray-300">
                                    <input type="number" name="scores[{{ $student->id }}][exam]" min="0"
                                        max="100" step="0.01" class="border p-1 rounded w-24" placeholder="eg. 60"
                                        value="{{ old("scores.{$student->id}.exam", $score->exam_score ?? '') }}" />
                                </td>
                                <td class="py-0.5 px-2 border-b border-gray-300">
                                    <input type="number" name="scores[{{ $student->id }}][exam]" min="0"
                                        max="100" step="0.01" class="border p-1 rounded w-24 bg-gray-300"
                                        value="{{ old("scores.{$student->id}.total", $score->total_score ?? '') }}"
                                        readonly disabled />
                                </td>
                                <td class="py-0.5 px-2 border-b border-gray-300">
                                    <input type="text" name="scores[{{ $student->id }}][grade]" min="0"
                                        max="100" step="0.01" class="border p-1 rounded w-24 bg-gray-300"
                                        value="{{ old("scores.{$student->id}.grade", $score->grade ?? '') }}"
                                        readonly disabled />
                                </td>
                                <td class="py-0.5 px-2 border-b border-gray-300">
                                    <input type="text" name="scores[{{ $student->id }}][position]" min="0"
                                        max="100" step="0.01" class="border p-1 rounded w-24 bg-gray-300"
                                        value="{{ old("scores.{$student->id}.position", $score->position ?? '') }}"
                                        readonly disabled />
                                </td>
                                <td class="py-0.5 px-2 border-b border-gray-300">
                                    <input type="text" name="scores[{{ $student->id }}][remark]" min="0"
                                        max="100" step="0.01" class="border p-1 rounded w-24 "
                                        value="{{ old("scores.{$student->id}.remark", $score->remark ?? '') }}"
                                        readonly disabled />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="mt-4 bg-green-900 text-white px-4 py-2 rounded cursor-pointer hover:underline">Submit Scores</button>
        </form>

    </div>
@endsection
