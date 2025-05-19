@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-6 pb-10">
        <form action="{{ route('scratch-codes.store') }}" method="POST" class="mb-6">
            @csrf
            <label for="count" class="block mb-1 font-semibold">Generate Scratch Codes:</label>
            <input type="number" name="count" id="count" class="form-control" placeholder="e.g. 20" required>
            <div class="mt-3">
                <button class="btn2">Generate</button>
            </div>

        </form>

        <h2 class="text-xl font-bold mb-2">Existing Scratch Codes</h2>
        <div class="flex justify-between items-center py-3">
            <form method="GET" action="{{ route('scratch-codes.index') }}" class="mb-4">
                <label for="uses_left" class="mr-2 font-semibold">Filter by Uses Left:</label>
                <select name="uses_left" id="uses_left" class="border rounded px-2 py-1 bg-white">
                    <option value="">All</option>
                    <option value="2" {{ request('uses_left') == '2' ? 'selected' : '' }}>Active</option>
                    <option value="1" {{ request('uses_left') == '1' ? 'selected' : '' }}>Pending</option>
                    <option value="0" {{ request('uses_left') == '0' ? 'selected' : '' }}>Used</option>
                </select>

                <button type="submit" class="ml-2 px-3 py-1 bg-slate-800 hover:underline cursor-pointer text-white rounded">Filter</button>
            </form>

            <div class="">
                <button id="download" class="cursor-pointer hover:underline flex items-center space-x-2 ml-2 px-3 py-2 bg-slate-800 text-white rounded">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </span>
                    <span>Download</span>
                </button>
            </div>
        </div>

        <div class="my-4">
            {{ $codes->appends(request()->query())->links() }}
        </div>
        <div id="content" class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Code
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Used
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Attached To</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Uses
                            Left</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Action
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-300">
                    @forelse ($codes as $code)
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900 font-semibold tracking-wide">
                                {{ $code->code }}</td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $code->uses_left < 2 ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $code->student ? $code->student->name : '-' }}
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $code->uses_left }}
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if ($code->uses_left <= 0)
                                    <form action="{{ route('scratch-codes.destroy', $code->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this used code?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-900 text-white px-3 text-xs py-2 rounded hover:underline cursor-pointer">Delete</button>
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
            {{ $codes->appends(request()->query())->links() }}
        </div>


    </div>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script>
        document.getElementById("download").addEventListener("click", () => {
            const element = document.getElementById("content");
            const opt = {
                margin: 0.5,
                filename: 'scratch-code.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                }, // Use 'png' for lossless compression
                html2canvas: {
                    scale: 2
                }, // Increase scale for better quality
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        });
    </script>
@endsection
