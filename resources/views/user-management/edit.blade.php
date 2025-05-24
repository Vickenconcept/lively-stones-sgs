@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto p-6">

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block font-semibold">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div>
                <label for="email" class="block font-semibold">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}"
                    required>
            </div>
            <div>
                <label for="password" class="block font-semibold">Password (leave blank to keep current)</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <button type="submit" class="btn">Update</button>
        </form>
    </div>
@endsection
