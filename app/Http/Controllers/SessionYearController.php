<?php

namespace App\Http\Controllers;

use App\Models\SessionYear;
use Illuminate\Http\Request;

class SessionYearController extends Controller
{
    public function index()
    {
        return view('session_years.index', ['sessionYears' => SessionYear::latest()->paginate(10)]);
    }
    public function create()
    {
        return view('session_years.create');
    }
    public function store(Request $r)
    {
        SessionYear::create($r->validate([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]));
        return redirect()->route('session_years.index')->with('success', 'session year updated successfully.');
    }
    public function edit(SessionYear $sessionYear)
    {
        return view('session_years.edit', compact('sessionYear'));
    }
    public function update(Request $r, SessionYear $SessionYear)
    {
        $SessionYear->update($r->validate([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]));
        return redirect()->route('session_years.index')->with('success', 'session year updated successfully.');
    }
    public function destroy(SessionYear $SessionYear)
    {
        $SessionYear->delete();
        return back();
    }
}
