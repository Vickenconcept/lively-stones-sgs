@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Classrooms</h1>
        <a href="{{ route('classrooms.create') }}" class="btn btn-primary">Create Classroom</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classrooms as $classroom)
                    <tr>
                        <td>{{ $classroom->name }}</td>
                        <td>{{ $classroom->section }}</td>
                        <td>
                            <a href="{{ route('classrooms.show', $classroom) }}" class="btn btn-sm btn-warning">Show</a>
                            <a href="{{ route('classrooms.edit', $classroom) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
