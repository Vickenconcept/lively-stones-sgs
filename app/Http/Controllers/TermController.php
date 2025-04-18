<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        return view('terms.index', ['terms' => Term::latest()->paginate(10)]);
    }
    public function create()
    {
        return view('terms.create');
    }
    public function store(Request $r)
    {
        Term::create($r->validate(['name' => 'required']));
        return redirect()->route('terms.index')->with('success', 'Created successfully.');

    }
    public function edit(Term $term)
    {
        return view('terms.edit', compact('term'));
    }
    public function update(Request $r, Term $term)
    {
        $term->update($r->validate(['name' => 'required']));
        return redirect()->route('terms.index')->with('success', 'Updated successfully.');
    }
    public function destroy(Term $term)
    {
        $term->delete();
        return back()->with('success', 'Deleted Successfully');
    }
}
