@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Edit Classroom</h1>

    <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium">Classroom Name</label>
            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('name', $classroom->name) }}" required>
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="capacity" class="block font-medium">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('capacity', $classroom->capacity) }}" required>
            @error('capacity')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update
        </button>
    </form>
</div>
@endsection
