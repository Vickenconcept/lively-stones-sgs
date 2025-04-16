@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Add Student</h1>
<form action="{{ route('students.store') }}" method="POST" class="space-y-4">
    @csrf
    <input type="text" name="name" placeholder="Name" class="border p-2 w-full" required>
    <input type="text" name="registration_number" placeholder="Registration Number" class="border p-2 w-full" required>
    <select name="classroom_id" class="border p-2 w-full">
        @foreach($classrooms as $classroom)
            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
</form>
@endsection