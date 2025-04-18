@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto p-6">

    <h1 class="text-xl font-bold mb-4">Add Student</h1>
    @php
    $regNumber = strtoupper('REG' . rand(100, 999) . chr(rand(65, 90)) . chr(rand(65, 90)));
@endphp
    <form action="{{ route('students.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Name" class="form-control" required autocomplete="off">
        {{-- <input type="text" name="registration_number" placeholder="Registration Number" class="form-control" required> --}}
        <input type="text" name="registration_number" value="{{ $regNumber }}" readonly class="form-control" required autocomplete="off">
        <select name="classroom_id" class="form-control">
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn">Save</button>
    </form>
</div>

@endsection