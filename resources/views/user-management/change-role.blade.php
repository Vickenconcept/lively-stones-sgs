@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">

    <h2 class="text-xl font-bold mb-4">Change Role for {{ $user->name }}</h2>
    <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="role" class="block font-semibold">Role</label>
            <select name="role" id="role" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{ $role }}" @if($user->getRoleNames()->first() == $role) selected @endif>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn">Update Role</button>
    </form>
</div>
@endsection 