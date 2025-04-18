@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Subject</h1>
    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <!-- Subject Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Subject Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="eg. English Language" value="{{ old('name', $subject->name) }}" required>
            </div>

            <!-- Subject Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="eg. enter description" required>{{ old('description', $subject->description) }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn">
                Update Subject
            </button>
        </div>
    </form>
</div>
@endsection
