<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $majors = Major::query()
            ->when(
                $search,
                fn($query) =>
                $query->where('name', 'like', '%' . $search . '%')
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.majors.index', compact('majors'));
    }

    public function create()
    {
        return view('admin.majors.edit');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:majors,name',
        ], [
            'name.required' => 'Jurusan wajib diisi.',
            'name.string' => 'Jurusan harus berupa teks.',
            'name.max' => 'Jurusan tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Jurusan sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Major::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            DB::commit();

            return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jurusan.');
        }
    }

    public function edit(Major $major)
    {
        return view('admin.majors.edit', compact('major'));
    }

    public function update(Request $request, Major $major)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:majors,name,' . $major->id,
        ], [
            'name.required' => 'Jurusan wajib diisi.',
            'name.string' => 'Jurusan harus berupa teks.',
            'name.max' => 'Jurusan tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Jurusan sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $major->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            DB::commit();

            return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui jurusan.');
        }
    }
    public function destroy(Major $major)
    {
        if (
            $major->rooms()->exists() ||
            $major->subjects()->exists()
        ) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus jurusan yang sedang digunakan di ruang kelas atau mata pelajaran.');
        }

        DB::beginTransaction();

        try {
            $major->delete();

            DB::commit();

            return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus jurusan.');
        }
    }
}
