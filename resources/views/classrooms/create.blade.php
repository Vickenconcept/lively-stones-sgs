@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Create Classroom</h1>

    <form action="{{ route('classrooms.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-medium">Classroom Name</label>
            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="capacity" class="block font-medium">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('capacity') }}" required>
            @error('capacity')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Save
        </button>
    </form>
</div>
@endsection
