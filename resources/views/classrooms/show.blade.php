@extends('layouts.app')

@section('content')
    <h2>Students</h2>
    <form method="GET" action="{{ route('classrooms.show', $classroom) }}" class="mb-4">
        <div class="flex space-x-4">
            <div>
                <label for="term_id">term:</label>
                <select name="term_id" id="term_id" class="border rounded p-1">
                    @foreach (\App\Models\Term::all() as $term)
                        <option value="{{ $term->id }}" {{ request('term_id') == $term->id ? 'selected' : '' }}>
                            {{ $term->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="session_year_id">session_year:</label>
                <select name="session_year_id" id="session_year_id" class="border rounded p-1">
                    @foreach (\App\Models\SessionYear::all() as $session_year)
                        <option value="{{ $session_year->id }}"
                            {{ request('session_year_id') == $session_year->id ? 'selected' : '' }}>
                            {{ $session_year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filter</button>
            </div>
        </div>
    </form>

    @if (request('term_id') && request('session_year_id'))
        <div>
            <form method="POST" action="{{ route('results.calculate') }}">
                @csrf
                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                <input type="hidden" name="term_id" value="{{ request('term_id') }}">
                <input type="hidden" name="session_year_id" value="{{ request('session_year_id') }}">

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Calculate Results
                </button>
            </form>
        </div>
    @endif
    {{-- <ul>
        @foreach ($students as $student)
            <li>{{ $student->name }} - {{ $student->results->first()?->position ?? 'N/A' }} <a href="">view
                    result</a></li>
        @endforeach
    </ul> --}}

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->results->first()?->position ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('results.student') }}" method="GET" target="_blank">
                            @csrf

                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                            <input type="hidden" name="term_id" value="{{ request('term_id') }}">
                            <input type="hidden" name="session_year_id" value="{{ request('session_year_id') }}">

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                                View Result
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $students->links() }}
    </div>

    <hr>

    <h2>Class Subject Terms</h2>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Term</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($classSubjectTerms as $data)
                <tr>
                    <td>{{ $data->subject->name }}</td>
                    <td>{{ $data->term->name }} ({{ $data->sessionYear->name }}) </td>
                    <td>
                        <a href="{{ route('class_subject_terms.upload_score_form', $data) }}"
                            class="btn btn-sm btn-warning">Upload score</a>

                        <form action="{{ route('class_subject_terms.destroy', $data->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>

    </table>
@endsection
