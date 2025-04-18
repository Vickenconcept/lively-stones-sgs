@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <!-- Add New Term Button -->
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold mb-6">Terms</h1>
        <a href="{{ route('terms.create') }}" onclick="return false;">
           <button class="btn2 !cursor-not-allowed" disabled>
            + Add New Term
           </button>
        </a>
    </div>

    <!-- Terms Table -->
    <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Term Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($terms as $term)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $term->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $term->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('terms.edit', $term->id) }}" onclick="return false;" class="bg-green-900 text-white px-3 text-xs py-2 rounded hover:underline !cursor-not-allowed" disabled>
                                    Edit
                                </a>
                                
                                <form action="{{ route('terms.destroy', $term->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer !cursor-not-allowed" disabled>
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
                                <i class='bx bxs-school text-3xl'></i>
                            </div>
                            <p>No Active Term</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $terms->links() }}
    </div>
</div>
@endsection
