<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\ScratchCode;
use Illuminate\Support\Str;

class ScratchCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = ScratchCode::query();

        if ($request->has('uses_left') && $request->uses_left != "") {
            $query->where('uses_left', (int)$request->uses_left);
        }

        $codes = $query->latest()->paginate(20);

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

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:scratch_codes,id',
        ]);

        $ids = $validated['ids'];

        $deleted = ScratchCode::whereIn('id', $ids)->where('uses_left', '<=', 0)->delete();

        $notDeleted = count($ids) - $deleted;
        $msg = $deleted . ' used code(s) deleted.' . ($notDeleted > 0 ? " {$notDeleted} code(s) skipped because they are not used yet." : '');

        return back()->with('success', $msg);
    }
}
