@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-6 pb-10">
        <form action="{{ route('scratch-codes.store') }}" method="POST" class="mb-6">
            @csrf
            <label for="count" class="block mb-1 font-semibold">Generate Scratch Codes:</label>
            <input type="number" name="count" id="count" class="form-control" placeholder="e.g. 20"
                required>
            <div class="mt-3">
                <button class="btn2">Generate</button>
            </div>

        </form>

        <h2 class="text-xl font-bold mb-2">Existing Scratch Codes</h2>
        <div class="my-4">
            {{ $codes->links() }}
        </div>
        <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Code</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Used</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Attached To</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Uses Left</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-300">
                    @forelse ($codes as $code)
                        <tr>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900 font-semibold tracking-wide">{{ $code->code }}</td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $code->uses_left < 2 ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $code->student ? $code->student->name : '-' }}
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $code->uses_left }}</td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if ($code->uses_left <= 0)
                                    <form action="{{ route('scratch-codes.destroy', $code->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this used code?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="bg-white text-center font-semibold p-5">
                                <div>
                                    <i class='bx bx-barcode text-3xl'></i>
                                </div>
                                <p>No Active Code</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $codes->links() }}
        </div>
    </div>
@endsection
