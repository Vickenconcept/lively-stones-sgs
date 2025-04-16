@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Students</h1>
<a href="{{ route('students.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Student</a>

<form method="GET" action="{{ route('students.index') }}" class="mb-4">
    <div class="flex space-x-4">
        <div>
            <label for="class_id">class:</label>
            <select name="class_id" id="class_id" class="border rounded p-1">
                @foreach (\App\Models\Classroom::all() as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filter</button>
        </div>
    </div>
</form>
<table class="table-auto w-full mt-4">
    <thead>
        <tr>
            <th>Name</th>
            <th>Registration Number</th>
            <th>Classroom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->registration_number }}</td>
            <td>{{ $student->classroom->name }}</td>
            <td>
                <a href="{{ route('students.edit', $student) }}" class="text-blue-500">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div>
    {{ $students->links() }}
</div>
@endsection