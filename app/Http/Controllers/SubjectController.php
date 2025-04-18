<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return view('subjects.index', ['subjects' => Subject::latest()->paginate(10)]);
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
