<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $levels = Level::query()
            ->when(
                $search,
                fn($query) =>
                $query->where('name', 'like', '%' . $search . '%')
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.edit');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:levels,name',
        ], [
            'name.required' => 'Tingkat Kelas wajib diisi.',
            'name.string' => 'Tingkat Kelas harus berupa teks.',
            'name.max' => 'Tingkat Kelas tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Tingkat Kelas sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Level::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            DB::commit();

            return redirect()->route('admin.levels.index')->with('success', 'Tingkat Kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan Tingkat Kelas.');
        }
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:levels,name,' . $level->id,
        ], [
            'name.required' => 'Tingkat Kelas wajib diisi.',
            'name.string' => 'Tingkat Kelas harus berupa teks.',
            'name.max' => 'Tingkat Kelas tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Tingkat Kelas sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $level->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            DB::commit();

            return redirect()->route('admin.levels.index')->with('success', 'Tingkat Kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui Tingkat Kelas.');
        }
    }

    public function destroy(Level $level)
    {
        if ($level->rooms()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus tingkat kelas yang sedang digunakan.');
        }

        DB::beginTransaction();

        try {
            $level->delete();

            DB::commit();

            return redirect()->route('admin.levels.index')->with('success', 'Tingkat Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus Tingkat Kelas.');
        }
    }
}
