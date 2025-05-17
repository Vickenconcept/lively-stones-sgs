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
        return redirect()->route('session_years.index')->with('success', 'Session year updated successfully.');
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
        return redirect()->route('session_years.index')->with('success', 'Session year updated successfully.');
    }

    public function status_update(Request $request, $id)
    {
        SessionYear::where('is_active', '1')->update(['is_active' => '0']);

        $session = SessionYear::findOrFail($id);
        $session->is_active = '1';
        $session->save();
        
        session()->put('session_year_id', $session->id);

        return redirect()->route('session_years.index')->with('success', 'Session year updated successfully.');
    }

    public function destroy(SessionYear $SessionYear)
    {
        $SessionYear->delete();
        return back()->with('success', 'Deleted Successfully');
    }
}
