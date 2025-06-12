@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Register New Student</h1>

    <form action="{{ route('students.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-medium">Student Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="registration_number" class="block font-medium">Registration Number</label>
            <input type="text" name="registration_number" id="registration_number" class="form-control" value="{{ old('registration_number') }}" required>
        </div>

        <div>
            <label for="classroom_id" class="block font-medium">Class</label>
            <select name="classroom_id" id="classroom_id" class="form-control" required>
                <option value="">Select a class</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="batch_id" class="block font-medium">Batch</label>
            <select name="batch_id" id="batch_id" class="form-control" required>
                <option value="">Select a batch</option>
            </select>
        </div>

        <button type="submit" class="btn">
            Register Student
        </button>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('classroom_id').addEventListener('change', function() {
        const classroomId = this.value;
        const batchSelect = document.getElementById('batch_id');
        
        // Clear current options
        batchSelect.innerHTML = '<option value="">Select a batch</option>';
        
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