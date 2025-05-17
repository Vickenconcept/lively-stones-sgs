@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-6">

        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold mb-6">Session Years</h1>
            <a href="{{ route('session_years.create') }}">

                <button class="btn2">
                    + Add New Session
                </button>
            </a>

        </div>

        <div class="my-4">
            {{ $sessionYears->links() }}
        </div>
        <!-- Session Years Table -->
        <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Start Date
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            End Date
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($sessionYears as $sessionYear)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $sessionYear->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($sessionYear->start_date)->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($sessionYear->end_date)->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <form action="{{ route('session.active', $sessionYear->id) }}" method="POST">
                                        @csrf
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                                {{ $sessionYear->is_active ? 'checked' : '' }}
                                                onchange="this.form.submit(); this.disabled = true;">
                                            <div
                                                class="w-11 z-0 h-6 bg-gray-400 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-400 rounded-full peer
                                                       peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white
                                                       after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300
                                                       after:border after:rounded-full after:w-5 after:h-5 after:transition-all peer-checked:bg-green-400">
                                            </div>
                                        </label>
                                    </form>

                                    <a href="{{ route('session_years.edit', $sessionYear->id) }}"
                                        class="bg-green-900 text-white px-3 text-xs py-2 rounded hover:underline ">
                                        Edit
                                    </a>

                                    <form action="{{ route('session_years.destroy', $sessionYear->id) }}" method="POST"
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
                            <td colspan="4" class="bg-white text-center font-semibold p-5">
                                <div>
                                    <i class='bx bxs-school text-3xl'></i>
                                </div>
                                <p>No Active Session</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $sessionYears->links() }}
        </div>
    </div>
@endsection
