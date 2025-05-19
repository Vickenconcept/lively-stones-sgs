@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Class Subject Terms</h1>
            <a href="{{ route('class_subject_terms.create') }}">
                <button class="btn2">
                    + Assign Subject
                </button>
            </a>
        </div>

        <div class="">
            <form method="GET" action="{{ route('class_subject_terms.index') }}" class="mb-6 border-b-2 border-gray-300 pb-4">
                <div class="flex items-end space-x-4">
                    <div>
                        <label for="class_id" class="text-sm font-semibold">Class</label>
                        <div>
                            <select name="class_id" id="class_id" class="form-control">
                                @foreach (\App\Models\Classroom::all() as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
            </form>
        </div>
        
        <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Classroom
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Subject
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Term</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Session
                            Year</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse ($classSubjectTerms as $entry)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $entry->classroom->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $entry->subject->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $entry->term->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $entry->sessionYear->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex gap-2">
                                <div class="flex space-x-3">
                                    <a href="{{ route('class_subject_terms.edit', $entry->id) }}"
                                        class="bg-green-900 text-white px-3 text-xs py-2 rounded hover:underline">Edit</a>
                                    <form action="{{ route('class_subject_terms.destroy', $entry->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button class="bg-red-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="bg-white text-center font-semibold p-5">
                                <div>
                                    <i class='bx bxs-book-content text-3xl'></i>
                                </div>
                                <p>No Active Data</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $classSubjectTerms->appends(request()->query())->links() }}
    </div>
@endsection
