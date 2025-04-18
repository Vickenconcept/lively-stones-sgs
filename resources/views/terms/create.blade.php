@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Add New Term</h1>

    <form action="{{ route('terms.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <!-- Term Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Term Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="eg. 1st" value="{{ old('name') }}" required>
            </div>

            <!-- Term Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="enter description" required>{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn">
                Save Term
            </button>
        </div>
    </form>
</div>
@endsection
