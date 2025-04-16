@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Edit Student</h1>
<form action="{{ route('students.update', $student) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $student->name }}" class="border p-2 w-full">
    <input type="text" name="registration_number" value="{{ $student->registration_number }}" class="border p-2 w-full">
    <select name="classroom_id" class="border p-2 w-full">
        @foreach($classrooms as $classroom)
            <option value="{{ $classroom->id }}" {{ $classroom->id == $student->classroom_id ? 'selected' : '' }}>{{ $classroom->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
</form>
@endsection