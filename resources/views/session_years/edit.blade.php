@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Edit Session Year</h1>

    <form action="{{ route('session_years.update', $sessionYear->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium">Session Year Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="eg. 2020/2021" value="{{ old('name', $sessionYear->name) }}" required>
        </div>

        <div>
            <label for="start_date" class="block font-medium">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $sessionYear->start_date) }}" required>
        </div>

        <div>
            <label for="end_date" class="block font-medium">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $sessionYear->end_date) }}" required>
        </div>

        <button type="submit" class="btn">
            Update
        </button>
    </form>
</div>
@endsection
