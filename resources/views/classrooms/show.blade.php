@extends('layouts.app')

@section('title')
    {{ $classroom->name }}
@stop
@section('content')
    <div class="max-w-6xl mx-auto p-6">

        {{-- <h2>Students</h2> --}}
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
                        <button type="submit" class="btn">Filter</button>
                    </div>
                </div>
                <input type="hidden" name="students" value="{{ request('students', 1) }}">
                <input type="hidden" name="classSubjects" value="{{ request('classSubjects', 1) }}">
                {{-- <input type="hidden" name="page" value="{{ request('page', 1) }}"> --}}
            </form>
        </div>

        @if (request('term_id') && request('session_year_id'))
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


        <main class="grid md:grid-cols-2 gap-3">
            <section>
                <div class="my-5 flex justify-between">
                    <h2 class="text-2xl font-medium ">Students</h2>
                    <button type="button" id="trigger-promotion"
                        class="bg-green-900 text-white px-4 py-2 rounded hover:underline hidden cursor-pointer">
                        Promote Students
                    </button>
                </div>
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
                                                {{ $student->results->first()?->position ?? 'N/A' }}</td>
                                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{-- <form action="{{ route('results.student') }}" method="GET" target="_blank">
                                                    @csrf
    
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="term_id" value="{{ request('term_id') }}">
                                                    <input type="hidden" name="session_year_id"
                                                        value="{{ request('session_year_id') }}">
    
                                                    <button type="submit" 
                                                        class="bg-blue-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer ">
                                                        View Result
                                                    </button>
                                                </form> --}}
                                                <button type="button"
                                                    class="bg-blue-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer "
                                                    onclick="viewResult({{ $student->id }})">
                                                    View Result
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="bg-white text-center font-semibold p-5">
                                                <div>
                                                    <i class='bx bxs-group text-3xl'></i>
                                                </div>
                                                <p>No Active Student</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <div class="mt-4">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </section>
                {{-- old students --}}
                <section>
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
                                                    <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                                        class="student-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                                </div>

                                            </td>
                                        @endif
                                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $student->name }}
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $student->results->first()?->position ?? 'N/A' }}</td>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{-- <form action="{{ route('results.student') }}" method="GET" target="_blank">
                                                    @csrf
    
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="term_id" value="{{ request('term_id') }}">
                                                    <input type="hidden" name="session_year_id"
                                                        value="{{ request('session_year_id') }}">
    
                                                    <button type="submit" 
                                                        class="bg-blue-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer ">
                                                        View Result
                                                    </button>
                                                </form> --}}
                                            <button type="button"
                                                class="bg-blue-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer "
                                                onclick="viewResult({{ $student->id }})">
                                                View Result
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="bg-white text-center font-semibold p-5">
                                            <div>
                                                <i class='bx bxs-group text-3xl'></i>
                                            </div>
                                            <p>No Active Student</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="overflow-x-auto">
                        <div class="mt-4">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </section>

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
            const url =
                `{{ route('results.student') }}?student_id=${studentId}&term_id=${termId}&session_year_id=${sessionYearId}`;
            window.open(url, '_blank');
        }
    </script>
@endsection
