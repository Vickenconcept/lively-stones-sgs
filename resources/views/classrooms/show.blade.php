@extends('layouts.app')

@section('title')
    {{ $classroom->name }}
@stop
@section('content')
    <div class="max-w-6xl mx-auto p-6">
        @if (!session('session_year_id'))
            <div class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                    <div
                        class="inline-block align-bottom relative z-50 bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Notice
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm leading-5 text-gray-500">
                                        No active session yet! Select a session.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="">
            <form method="GET" action="{{ route('classrooms.show', $classroom) }}" class="mb-6">
                <div class="flex items-end space-x-4">
                    <div>
                        <label for="term_id" class="text-sm font-semibold">Term</label>
                        <div>
                            <select name="term_id" id="term_id" class="form-control">
                                @foreach (\App\Models\Term::all() as $term)
                                    <option value="{{ $term->id }}"
                                        {{ request('term_id') == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="session_year_id" class="text-sm font-semibold">Session year</label>
                        <div>
                            @php
                                $selectedSessionId = request('session_year_id') ?? session('session_year_id');
                            @endphp

                            <select name="session_year_id" id="session_year_id" class="form-control">
                                @foreach (\App\Models\SessionYear::all() as $session_year)
                                    <option value="{{ $session_year->id }}"
                                        {{ $selectedSessionId == $session_year->id ? 'selected' : '' }}>
                                        {{ $session_year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="batch_id" class="text-sm font-semibold">Batch</label>
                        <div>
                            <select name="batch_id" id="batch_id" class="form-control">
                                <option value="">All Batches</option>
                                @php
                                    use App\Models\Batch;
                                @endphp
                                @foreach(Batch::all() as $batch)
                                    <option value="{{ $batch->id }}"
                                        {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn">Filter</button>
                    </div>
                </div>
                <input type="hidden" name="students" value="{{ request('students', 1) }}">
                <input type="hidden" name="classSubjects" value="{{ request('classSubjects', 1) }}">
            </form>
        </div>

        {{-- Empty state when filters are not set --}}
        @if (!request('term_id') || !request('session_year_id'))
            <div class="my-8">
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="mb-4">
                        <i class='bx bx-filter-alt text-6xl text-gray-400'></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Select Filters to View Data</h3>
                    <p class="text-gray-500 mb-4">
                        Please select a term and session year above to view students, results, and subjects for this class.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <i class='bx bx-calendar text-blue-500 text-xl mb-1'></i>
                            <p class="text-sm text-blue-700 font-medium">Term</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <i class='bx bx-calendar-check text-green-500 text-xl mb-1'></i>
                            <p class="text-sm text-green-700 font-medium">Session Year</p>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                            <i class='bx bx-group text-purple-500 text-xl mb-1'></i>
                            <p class="text-sm text-purple-700 font-medium">Students & Results</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Content when filters are set --}}
            @if (request('session_year_id') == session('session_year_id'))
            @if (request('term_id') && request('session_year_id') && $students->count() > 0)
                <div>
                    <form method="POST" action="{{ route('results.calculate') }}">
                        @csrf
                        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                        <input type="hidden" name="term_id" value="{{ request('term_id') }}">
                        <input type="hidden" name="session_year_id" value="{{ request('session_year_id') }}">

                        <button type="submit" class="btn2">
                            <i class='bx bxs-category-alt text-xl mr-3'></i>
                            <span>Calculate Results</span>
                        </button>
                    </form>
                </div>
            @endif
        @endif

        <main class="grid md:grid-cols-2 gap-3">
            <section>
                @if (request('session_year_id') == session('session_year_id'))
                    <div class="my-5 flex justify-between">
                        <h2 class="text-2xl font-medium ">Current Students</h2>
                        <button type="button" id="trigger-promotion"
                            class="bg-green-900 text-white px-4 py-2 rounded hover:underline hidden cursor-pointer">
                            Promote Students
                        </button>
                    </div>
                @endif

                @if (request('session_year_id') == session('session_year_id'))
                    {{-- Current students --}}
                    <section>
                        <form id="promote-form" method="POST" action="{{ route('students.promote') }}">
                            @csrf
                            <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            @if (request()->has('term_id') && request()->term_id == 3)
                                                <th scope="col" class="px-3 py-3 ">
                                                    <div class="flex items-center">
                                                        <input id="checked-all" type="checkbox"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                                    </div>
                                                </th>
                                            @endif
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                                Batch
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                                Position
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm divide-y divide-gray-300">
                                        @forelse ($students as $student)
                                            <tr>
                                                @if (request()->has('term_id') && request()->term_id == 3)
                                                    <td class="px-3 py-2">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" name="students[]"
                                                                value="{{ $student->id }}"
                                                                class="student-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                                        </div>
                                                    </td>
                                                @endif
                                                <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $student->name }}
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $student->batch->name }}
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $student->results->first()?->position ?? 'N/A' }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <button type="button"
                                                        class="bg-blue-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer "
                                                        onclick="viewResult({{ $student->id }})">
                                                        View Result
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="bg-white text-center font-semibold p-5">
                                                    <div>
                                                        <i class='bx bxs-group text-3xl'></i>
                                                    </div>
                                                    <p>No Current Students</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="term_id" value="{{ request('term_id') }}">
                            <input type="hidden" name="session_year_id" value="{{ request('session_year_id') }}">
                        </form>
                        <div class="overflow-x-auto">
                            <div class="mt-4">
                                {{ $students->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Historical students (show in both current and historical sessions) --}}
                @if($oldStudents->count() > 0)
                    <section class="mt-8">
                        <div class="my-5">
                            <h2 class="text-2xl font-medium text-gray-600">Historical Students (Previously in this class)</h2>
                        </div>
                        <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        @if (request()->has('term_id') && request()->term_id == 3)
                                            <th scope="col" class="px-3 py-3 ">
                                                <div class="flex items-center">
                                                    <input id="checked-all" type="checkbox"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                                </div>
                                            </th>
                                        @endif
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Batch
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Position
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-300">
                                    @forelse ($oldStudents as $student)
                                        <tr>
                                            @if (request()->has('term_id') && request()->term_id == 3)
                                                <td class="px-3 py-2">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="students[]"
                                                            value="{{ $student->id }}"
                                                            class="student-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->name }}
                                            </td>
                                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->batch->name }}
                                            </td>
                                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->results->first()?->position ?? 'N/A' }}</td>
                                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <button type="button"
                                                    class="bg-blue-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer "
                                                    onclick="viewResult({{ $student->id }})">
                                                    View Result
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="bg-white text-center font-semibold p-5">
                                                <div>
                                                    <i class='bx bxs-group text-3xl'></i>
                                                </div>
                                                <p>No Historical Students Found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($oldStudents instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="overflow-x-auto">
                            <div class="mt-4">
                                {{ $oldStudents->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif
                    </section>
                @endif
            </section>

            <section>
                <h2 class="text-2xl font-medium mb-3">Subjects assigned</h2>

                <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                    Term
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-300">
                            @forelse ($classSubjectTerms as $data)
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data->subject->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data->term->name }}
                                        ({{ $data->sessionYear->name }})
                                    </td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('class_subject_terms.upload_score_form', $data) }}"
                                                class="bg-green-900 text-white px-3 text-xs py-2 rounded hover:underline ">Upload
                                                score</a>

                                            <form action="{{ route('class_subject_terms.destroy', $data->id) }}"
                                                method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="bg-red-900 text-white px-3 text-xs py-2 rounded hover:underline  hover:underline">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="bg-white text-center font-semibold p-5">
                                        <div>
                                            <i class='bx bxs-book text-3xl'></i>
                                        </div>
                                        <p>No Active Subject</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="overflow-x-auto">
                    <div class="mt-4">
                        {{ $classSubjectTerms->appends(request()->query())->links() }}
                    </div>
                </div>
            </section>
        </main>
        @endif
    </div>

    <script>
        const checkAll = document.getElementById('checked-all');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const promoteBtnWrapper = document.getElementById('trigger-promotion');

        function togglePromoteButton() {
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            promoteBtnWrapper.classList.toggle('hidden', checkedCount === 0);
        }

        checkAll.addEventListener('change', function() {
            studentCheckboxes.forEach(cb => cb.checked = this.checked);
            togglePromoteButton();
        });

        studentCheckboxes.forEach(cb => {
            cb.addEventListener('change', togglePromoteButton);
        });
    </script>

    <script>
        const checkboxes = document.querySelectorAll('.student-checkbox');
        const promoteBtn = document.getElementById('trigger-promotion');
        const promoteForm = document.getElementById('promote-form');

        // Show/hide button if any checkbox is checked
        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const anyChecked = [...checkboxes].some(c => c.checked);
                promoteBtn.classList.toggle('hidden', !anyChecked);
            });
        });

        // Submit the form when the external button is clicked
        promoteBtn.addEventListener('click', () => {
            promoteForm.submit();
        });
    </script>

    <script>
        function viewResult(studentId) {
            const termId = '{{ request('term_id') }}';
            const sessionYearId = '{{ request('session_year_id') }}';
            const classroomId = '{{ $classroom->id }}';
            const url =
                `{{ route('results.student') }}?student_id=${studentId}&term_id=${termId}&session_year_id=${sessionYearId}&classroom_id=${classroomId}`;
            window.open(url, '_blank');
        }
    </script>
@endsection
