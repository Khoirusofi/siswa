<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class YearController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $years = Year::query()
            ->when(
                $search,
                fn($query) =>
                $query->where('name', 'like', '%' . $search . '%')
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.years.index', compact('years'));
    }

    public function create()
    {
        return view('admin.years.edit');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:years,name',
        ], [
            'name.required' => 'Tahun akademik wajib diisi.',
            'name.string' => 'Tahun akademik harus berupa teks.',
            'name.max' => 'Tahun akademik tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Tahun akademik sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Year::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            DB::commit();

            return redirect()->route('admin.years.index')->with('success', 'Tahun Akademik berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan Tahun Akademik.');
        }
    }

    public function edit(Year $year)
    {
        return view('admin.years.edit', compact('year'));
    }

    public function update(Request $request, Year $year)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:years,name,' . $year->id,
        ], [
            'name.required' => 'Tahun akademik wajib diisi.',
            'name.string' => 'Tahun akademik harus berupa teks.',
            'name.max' => 'Tahun akademik tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Tahun akademik sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $year->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            DB::commit();

            return redirect()->route('admin.years.index')->with('success', 'Tahun Akademik berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui Tahun Akademik.');
        }
    }

    public function destroy(Year $year)
    {
        if ($year->enrollments()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus Tahun Ajaran yang sedang digunakan.');
        }

        DB::beginTransaction();

        try {
            $year->delete();

            DB::commit();

            return redirect()->route('admin.years.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus Tahun Ajaran.');
        }
    }
}
