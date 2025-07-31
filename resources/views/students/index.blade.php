@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold mb-4">Students</h1>
            @role(['super-admin', 'admin'])
                <a href="{{ route('students.create') }}" >
                    <button class="btn2">
                        + Register Student
                    </button>
                </a>
            @endrole
        </div>

        <form method="GET" action="{{ route('students.index') }}" class="mb-4">
            <div class="flex items-end space-x-4">
                <div>
                    <label for="search" class="text-sm font-semibold">Search by Name</label>
                    <div>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Enter student name..."
                               class="form-control">
                    </div>
                </div>

                <div>
                    <label for="class_id" class="text-sm font-semibold">Class</label>
                   <div>
                    <select name="class_id" id="class_id" class="form-control">
                        <option value="">All Classes</option>
                        @foreach (\App\Models\Classroom::all() as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                   </div>
                </div>

                <div>
                    <label for="batch_id" class="text-sm font-semibold">Batch</label>
                    <div>
                        <select name="batch_id" id="batch_id" class="form-control">
                            <option value="">All Batches</option>
                            @php
                                use App\Models\Batch;
                            @endphp
                            @foreach(Batch::all() as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn">Filter</button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Name</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            Registration Number</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Class</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Batch</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse ($students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $student->registration_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $student->classroom->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $student->batch->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                @role(['super-admin', 'admin'])
                                    <a href="{{ route('students.edit', $student) }}" class="bg-green-900 text-white px-3 text-xs py-2 rounded hover:underline">Edit</a>
                                @endrole
                            </td>
                        </tr>
                        @empty
                        <td colspan="4" class="bg-white text-center font-semibold p-5">
                            <div class="flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                  </svg>
                                  
                            </div>
                            <p>No Active Student</p>
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $students->appends(request()->query())->links() }}
        </div>

    </div>

    @push('scripts')
    <script>
        document.getElementById('class_id').addEventListener('change', function() {
            const classroomId = this.value;
            const batchSelect = document.getElementById('batch_id');
            
            // Clear current options
            batchSelect.innerHTML = '<option value="">All Batches</option>';
            
            if (classroomId) {
                // Fetch batches for selected classroom
                fetch(`/students/batches/${classroomId}`)
                    .then(response => response.json())
                    .then(batches => {
                        batches.forEach(batch => {
                            const option = document.createElement('option');
                            option.value = batch.id;
                            option.textContent = batch.name;
                            batchSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
    @endpush
@endsection
