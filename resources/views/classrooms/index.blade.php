@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-6">
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Classrooms</h1>
            <a href="{{ route('classrooms.create') }}">
                <button class="btn2">
                    + Create Classroom
                </button>
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Name</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Section
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($classrooms as $classroom)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $classroom->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $classroom->section }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex space-x-3">
                                    <a href="{{ route('classrooms.show', $classroom) }}" class="bg-yellow-600 text-white px-3 text-xs py-2 rounded hover:underline">Go to</a>
                                    <a href="{{ route('classrooms.edit', $classroom) }}" class="bg-green-900 text-white px-3 text-xs py-2 rounded hover:underline">Edit</a>
                                    <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="bg-red-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer " onsubmit="return confirm('Are you sure?');">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="bg-white text-center font-semibold p-5">
                                <div class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                      </svg>
                                      
                                </div>
                                <p>No Active Class</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
