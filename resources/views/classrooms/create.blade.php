@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Create Classroom</h1>

    <form action="{{ route('classrooms.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-medium">Classroom Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="eg. JSS 1" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="capacity" class="block font-medium">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control" placeholder="eg. 30" value="{{ old('capacity') }}" required>
        </div>

        <button type="submit" class="btn">
            Save
        </button>
    </form>
</div>
@endsection
