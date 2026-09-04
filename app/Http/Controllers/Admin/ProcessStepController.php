<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    public function index()
    {
        $steps = ProcessStep::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.process-steps', compact('steps'));
    }

    public function store(Request $request)
    {
        $baru = ProcessStep::create($this->validated($request));

        return $this->kembaliKeBaris('tahap', $baru, 'Tahap kerja berhasil ditambahkan.');
    }

    public function update(Request $request, ProcessStep $processStep)
    {
        $processStep->update($this->validated($request));

        return $this->kembaliKeBaris('tahap', $processStep, 'Tahap kerja berhasil diperbarui.');
    }

    public function destroy(ProcessStep $processStep)
    {
        $processStep->delete();

        return $this->kembaliKeBaris('tahap', null, 'Tahap kerja berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'icon' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
