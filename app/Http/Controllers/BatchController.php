<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::withCount('students')->get();
        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        return view('batches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:batches',
            'description' => 'nullable|string'
        ]);

        Batch::create($request->all());
        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    public function edit(Batch $batch)
    {
        return view('batches.edit', compact('batch'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:batches,name,' . $batch->id,
            'description' => 'nullable|string'
        ]);

        $batch->update($request->all());
        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
} 