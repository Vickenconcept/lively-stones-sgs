@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-6">

        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold mb-6">Subjects</h1>
            <a href="{{ route('subjects.create') }}">
                <button class="btn2">+ Add New Subject</button>
            </a>
        </div>

        <!-- Subjects Table -->
        <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Subject Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($subjects as $subject)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $subject->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $subject->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('subjects.edit', $subject->id) }}"
                                        class="bg-green-900 text-white px-3 text-xs py-2 rounded hover:underline ">
                                        Edit
                                    </a>
                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="bg-white text-center font-semibold p-5">
                                <div>
                                    <i class='bx bxs-book-reader text-3xl'></i>
                                </div>
                                <p>No Active Subject</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $subjects->links() }}
        </div>
    </div>
@endsection
