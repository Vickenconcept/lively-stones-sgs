@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Class Subject Terms</h1>
            <a href="{{ route('class_subject_terms.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                + Assign Subject
            </a>
        </div>

        <form method="GET" action="{{ route('class_subject_terms.index') }}" class="mb-4">
            <div class="flex space-x-4">
                <div>
                    <label for="class_id">class:</label>
                    <select name="class_id" id="class_id" class="border rounded p-1">
                        @foreach (\App\Models\Classroom::all() as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
                            <option value="{{ $session_year->id }}" {{ request('session_year_id') == $session_year->id ? 'selected' : '' }}>
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

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                    <tr>
                        <th class="px-4 py-2">Classroom</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Term</th>
                        <th class="px-4 py-2">Session Year</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @foreach ($classSubjectTerms as $entry)
                        <tr>
                            <td class="px-4 py-2">{{ $entry->classroom->name }}</td>
                            <td class="px-4 py-2">{{ $entry->subject->name }}</td>
                            <td class="px-4 py-2">{{ $entry->term->name }}</td>
                            <td class="px-4 py-2">{{ $entry->sessionYear->name }}</td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{ route('class_subject_terms.edit', $entry->id) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('class_subject_terms.destroy', $entry->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $classSubjectTerms->links() }}
    </div>
@endsection
