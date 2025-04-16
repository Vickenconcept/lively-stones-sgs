<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        return view('classrooms.index', ['classrooms' => Classroom::all()]);
    }
    public function create()
    {
        return view('classrooms.create');
    }
    public function store(Request $r)
    {
        Classroom::create($r->validate(['name' => 'required']));
        return redirect()->route('classrooms.index')->with('success', 'Classroom updated successfully.');
    }


    public function show(Classroom $classroom, Request $request)
    {
        $termId = $request->term_id;
        $sessionYearId = $request->session_year_id;


        $students = $classroom->students()
            ->with(['results' => function ($query) use ($termId, $sessionYearId) {
                $query->where('term_id', $termId)
                    ->where('session_year_id', $sessionYearId);
            }])
            ->paginate(5);

        $classSubjectTerms = $classroom->classSubjectTerms()
            ->when($termId, function ($query) use ($termId) {
                $query->where('term_id', $termId);
            })
            ->when($sessionYearId, function ($query) use ($sessionYearId) {
                $query->where('session_year_id', $sessionYearId);
            })
            ->with(['subject', 'term', 'sessionYear'])
            ->get();

        return view('classrooms.show', compact('classroom', 'students', 'classSubjectTerms'));
    }


    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id,
            // 'capacity' => 'required|integer|min:1',
        ]);

        $classroom->update($request->only('name'));

        return redirect()->route('classrooms.index')->with('success', 'Classroom updated successfully.');
    }
    public function destroy(Classroom $c)
    {
        $c->delete();
        return back();
    }
}
