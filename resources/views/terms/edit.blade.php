@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Term</h1>
    <!-- Form to Edit Term -->
    <form action="{{ route('terms.update', $term->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <!-- Term Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Term Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="eg. 1st" value="{{ old('name', $term->name) }}" required>
            </div>

            <!-- Term Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="enter description" required>{{ old('description', $term->description) }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn">
                Update Term
            </button>
        </div>
    </form>
</div>
@endsection
