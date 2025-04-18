@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto p-6">
<h1 class="text-xl font-bold mb-4">Edit Student</h1>
<form action="{{ route('students.update', $student) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $student->name }}" class="form-control" autocomplete="off">
    <input type="text" name="registration_number" value="{{ $student->registration_number }}" class="form-control" readonly>
    <select name="classroom_id" class="form-control">
        @foreach($classrooms as $classroom)
            <option value="{{ $classroom->id }}" {{ $classroom->id == $student->classroom_id ? 'selected' : '' }}>{{ $classroom->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn">Update</button>
</form>
</div>
@endsection