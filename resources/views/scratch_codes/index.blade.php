@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-6">
        @if (session('success'))
            <div class="bg-green-200 p-3 rounded mb-4 text-green-800">{{ session('success') }}</div>
        @endif

        <form action="{{ route('scratch-codes.store') }}" method="POST" class="mb-6">
            @csrf
            <label for="count" class="block mb-1 font-semibold">Generate Scratch Codes:</label>
            <input type="number" name="count" id="count" class="border rounded w-full mb-2 p-2" placeholder="e.g. 20"
                required>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Generate</button>
        </form>

        <h2 class="text-xl font-bold mb-2">Existing Scratch Codes</h2>
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Code</th>
                        <th class="p-2">Used</th>
                        <th class="p-2">Attached To</th>
                        <th class="p-2">Uses Left</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($codes as $code)
                        <tr class="border-t">
                            <td class="p-2 font-mono">{{ $code->code }}</td>
                            <td class="p-2">{{ $code->uses_left < 2 ? 'Yes' : 'No' }}</td>
                            <td class="p-2">
                                {{ $code->student ? $code->student->name : '-' }}
                            </td>
                            <td class="p-2">{{ $code->uses_left }}</td>
                            <td class="p-2">
                                @if ($code->uses_left <= 0)
                                    <form action="{{ route('scratch-codes.destroy', $code->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this used code?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $codes->links() }}
        </div>
    </div>
@endsection
