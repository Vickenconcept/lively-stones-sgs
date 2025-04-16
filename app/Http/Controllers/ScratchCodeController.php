<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\ScratchCode;
use Illuminate\Support\Str;

class ScratchCodeController extends Controller
{
    public function index()
    {
        $codes = ScratchCode::latest()->paginate(20);
        return view('scratch_codes.index', compact('codes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:100',
        ]);

        for ($i = 0; $i < $request->count; $i++) {
            ScratchCode::create([
                'code' => strtoupper(Str::random(10)), // 10-char uppercase code
                'uses_left' => 2,
            ]);
        }

        return redirect()->back()->with('success', "{$request->count} scratch codes generated.");
    }


    public function destroy($id)
    {
        $code = ScratchCode::findOrFail($id);

        if ($code->uses_left <= 0) {
            $code->delete();
            return back()->with('success', 'Used scratch code deleted.');
        }

        return back()->withErrors(['message' => 'Only used codes can be deleted.']);
    }
}
