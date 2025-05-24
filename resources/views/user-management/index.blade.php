@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold mb-4">Staff List</h1>
    <a href="{{ route('users.create') }}" >
        <button class="btn2">
            + Add Staff
        </button>
    </a>
    </div>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border">Name</th>
                <th class="py-2 px-4 border">Email</th>
                <th class="py-2 px-4 border">Role</th>
                <th class="py-2 px-4 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="py-2 px-4 border">{{ $user->name }}</td>
                <td class="py-2 px-4 border">{{ $user->email }}</td>
                <td class="py-2 px-4 border">{{ $user->getRoleNames()->first() }}</td>
                <td class="py-2 px-4 border flex space-x-2">
                    <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline cursor-pointer">Delete</button>
                    </form>
                    <a href="{{ route('users.changeRole', $user->id) }}" class="text-green-600 hover:underline">Change Role</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 