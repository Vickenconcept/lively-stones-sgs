@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Edit Batch</h1>

    <form action="{{ route('batches.update', $batch->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium">Batch Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="eg. A, B, C" value="{{ old('name', $batch->name) }}" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block font-medium">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter batch description">{{ old('description', $batch->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn">
            Update Batch
        </button>
    </form>
</div>
@endsection 