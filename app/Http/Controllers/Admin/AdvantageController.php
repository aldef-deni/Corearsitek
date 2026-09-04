<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advantage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdvantageController extends Controller
{
    public function index()
    {
        return view('admin.advantages', [
            'advantages' => Advantage::orderBy('sort_order')->orderBy('id')->get()->groupBy('type'),
        ]);
    }

    public function store(Request $request)
    {
        Advantage::create($this->validated($request));

        return back()->with('success', 'Poin berhasil ditambahkan.');
    }

    public function update(Request $request, Advantage $advantage)
    {
        $advantage->update($this->validated($request));

        return back()->with('success', 'Poin berhasil diperbarui.');
    }

    public function destroy(Advantage $advantage)
    {
        $advantage->delete();

        return back()->with('success', 'Poin berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(array_keys(Advantage::TYPES))],
            'text' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
