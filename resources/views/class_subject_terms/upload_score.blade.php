@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6 " x-data="{ openModal: false }">

        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2>Upload Scores for
                    <span class="font-bold text-black">{{ $classSubjectTerm->subject->name }}</span> -
                    <span class="font-bold text-black">{{ $classSubjectTerm->term->name }}
                        ({{ $classSubjectTerm->sessionYear->name }})</span>
                </h2>
                @if ($editor)
                    <p class="mb-2 text-sm text-gray-600">Last edited by: <strong>{{ $editor->name }}</strong></p>
                @else
                    <p class="mb-2 text-sm text-gray-600">No edit history found.</p>
                @endif
            </div>
            <div>
                <button class="btn2 cursor-pointer" @click="openModal = true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                      </svg>
                       <span>Upload File</span>
                </button>

            </div>
        </div>

        <div class="mb-3">
            <p>Total students: <span
                    class="text-bold px-2 py-0.5 px-2  text-sm rounded-full bg-slate-700 text-yellow-400">{{ count($students ?? 0) }}</span>
            </p>
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
                            <tr>
                                <td class="py-0.5 px-2 border-b border-gray-300 pr-6 font-semibold">{{ $student->name }}
                                </td>
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
                                        value="{{ old("scores.{$student->id}.grade", $score->grade ?? '') }}" readonly
                                        disabled />
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
                                        value="{{ old("scores.{$student->id}.remark", $score->remark ?? '') }}" readonly
                                        disabled />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit"
                class="mt-4 bg-green-900 text-white px-4 py-2 rounded cursor-pointer hover:underline">Submit Scores</button>
        </form>



        {{-- excel modal --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <div x-show="openModal" style="display: none" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div @click.away="openModal = false"
                    class="inline-block align-bottom relative z-50 bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                    <h3 class="text-sm leading-6 font-semibold text-gray-900">
                        File example
                    </h3>
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center p-4 rounded bg-red-100 sm:mx-0 sm:h-auto sm:w-auto overflow-x-auto">
                        <table class="text-sm text-left text-gray-700 border border-gray-300 rounded shadow bg-white">
                            <thead class="bg-gray-200 text-gray-700">
                                <tr>
                                    <th class="p-1 border">registration_number</th>
                                    <th class="p-1 border">ca1_score</th>
                                    <th class="p-1 border">ca2_score</th>
                                    <th class="p-1 border">exam_score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-1 border">REG001</td>
                                    <td class="p-1 border">20</td>
                                    <td class="p-1 border">15</td>
                                    <td class="p-1 border">50</td>
                                </tr>
                                <tr>
                                    <td class="p-1 border">REG002</td>
                                    <td class="p-1 border">18</td>
                                    <td class="p-1 border">20</td>
                                    <td class="p-1 border">55</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="">
                        <div class="mt-3 text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Select File
                            </h3>
                            <div class="mt-2">
                                <form action="{{ route('classSubjectTerms.uploadScoresCsv', $classSubjectTerm) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="max-w-sm">
                                        <label class="block">
                                            <span class="sr-only">Choose profile photo</span>
                                            <input type="file" name="excel_file" accept=".csv,.xls,.xlsx" required
                                                class="block w-full text-sm text-gray-500
                                              file:me-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-600 file:text-white
                                              hover:file:bg-blue-700
                                              file:disabled:opacity-50 file:disabled:pointer-events-none
                                              dark:text-neutral-500
                                              dark:file:bg-blue-500
                                              dark:hover:file:bg-blue-400
                                            ">
                                        </label>
                                    </div>
                                    <small class="text-red-600">Accept .csv,.xls,.xlsx</small>
                                    {{-- <input type="file" name="excel_file" class="form-control"
                                        accept=".csv,.xls,.xlsx" required> --}}
                                    <button type="submit" @click="openModal = false" class="btn cursor-pointer mt-4">Upload CSV Scores</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
