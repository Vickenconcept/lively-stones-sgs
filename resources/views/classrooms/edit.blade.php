@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Edit Classroom</h1>

    <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium">Classroom Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="eg. JSS 1" value="{{ old('name', $classroom->name) }}" required>
        </div>

        <div>
            <label for="capacity" class="block font-medium">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control" placeholder="eg. 30" value="{{ old('capacity', $classroom->capacity) }}" required>
        </div>

        <button type="submit" class="btn">
            Update
        </button>
    </form>
</div>
@endsection
