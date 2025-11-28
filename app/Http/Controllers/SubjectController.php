<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $subjects = Subject::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('subjects.index', compact('subjects', 'search'));
    }
    public function create()
    {
        return view('subjects.create');
    }
    public function store(Request $r)
    {
        Subject::create($r->validate(['name' => 'required']));
        return redirect()->route('subjects.index')->with('success', 'Created successfully.');
    }
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }
    public function update(Request $r, Subject $subject)
    {
        $subject->update($r->validate(['name' => 'required']));
        return redirect()->route('subjects.index')->with('success', 'updated successfully.');
    }
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Deleted successfully.');
    }
}
