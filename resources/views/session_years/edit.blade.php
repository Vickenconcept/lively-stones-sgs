@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Edit Session Year</h1>

    <form action="{{ route('session_years.update', $sessionYear->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium">Session Year Name</label>
            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('name', $sessionYear->name) }}" required>
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="start_date" class="block font-medium">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('start_date', $sessionYear->start_date) }}" required>
            @error('start_date')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="end_date" class="block font-medium">End Date</label>
            <input type="date" name="end_date" id="end_date" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('end_date', $sessionYear->end_date) }}" required>
            @error('end_date')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update
        </button>
    </form>
</div>
@endsection
