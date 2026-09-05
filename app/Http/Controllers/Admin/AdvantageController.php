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
        return view('admin.benefit', [
            'advantages' => Advantage::orderBy('sort_order')->orderBy('id')->get()->groupBy('type'),
        ]);
    }

    public function store(Request $request)
    {
        $baru = Advantage::create($this->validated($request));

        return $this->kembaliKeBaris('poin', $baru, 'Poin berhasil ditambahkan.');
    }

    public function update(Request $request, Advantage $advantage)
    {
        $advantage->update($this->validated($request));

        return $this->kembaliKeBaris('poin', $advantage, 'Poin berhasil diperbarui.');
    }

    public function destroy(Advantage $advantage)
    {
        $advantage->delete();

        return $this->kembaliKeBaris('poin', null, 'Poin berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(array_keys(Advantage::TYPES))],
            'text' => ['required', 'string', 'max:255'],
            'text_en' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
