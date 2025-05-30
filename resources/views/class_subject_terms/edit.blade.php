@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Edit Assigned Subject</h1>

    <form action="{{ route('class_subject_terms.update', $classSubjectTerm->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Classroom</label>
            <select name="classroom_id" class="form-control">
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $classSubjectTerm->classroom_id == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Subject</label>
            <select name="subject_id" class="form-control">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $classSubjectTerm->subject_id == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Term</label>
            <select name="term_id" class="form-control">
                @foreach($terms as $term)
                    <option value="{{ $term->id }}" {{ $classSubjectTerm->term_id == $term->id ? 'selected' : '' }}>
                        {{ $term->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Session Year</label>
            <select name="session_year_id" class="form-control">
                @foreach($sessionYears as $session)
                    <option value="{{ $session->id }}" {{ $classSubjectTerm->session_year_id == $session->id ? 'selected' : '' }}>
                        {{ $session->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn">
            Update
        </button>
    </form>
</div>
@endsection
