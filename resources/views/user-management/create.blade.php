@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">

    <h1 class="text-xl font-bold mb-4">Add New Staff</h1>
    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block font-semibold">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
        </div>
        <div>
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
        </div>
        <div>
            <label for="password" class="block font-semibold">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
        </div>
        <div>
            <label for="role" class="block font-semibold">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="super-admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>
        <button type="submit" class="btn cursor-pointer">Register</button>
    </form>
</div>
@endsection 