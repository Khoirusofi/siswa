<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{

    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $subjects = Subject::query()->with('major')
            ->when(
                $search,
                fn($query) =>
                $query->where('name', 'like', '%' . $search . '%')
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $majors = Major::all();

        return view('admin.subjects.edit', compact(
            'majors'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:subjects,name',
            'major_id' => 'nullable|exists:majors,id',
            'is_general' => 'required|boolean',
        ], [
            'name.required' => 'Mata pelajaran wajib diisi.',
            'name.string' => 'Mata pelajaran harus berupa teks.',
            'name.max' => 'Mata pelajaran tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Mata pelajaran sudah digunakan.',
            'is_general.required' => 'Status umum wajib dipilih.',
            'is_general.boolean' => 'Status umum harus bernilai benar atau salah.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Subject::create([
                'name' => $request->name,
                'major_id' => $request->is_general ? null : $request->major_id,
                'is_general' => $request->is_general,
            ]);

            DB::commit();

            return redirect()->route('admin.subjects.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Mata Pelajaran.');
        }
    }

    public function edit(Subject $subject)
    {
        $majors = Major::all();

        return view('admin.subjects.edit', compact(
            'subject',
            'majors'
        ));
    }

    public function update(Request $request, Subject $subject)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'major_id' => 'nullable|exists:majors,id',
            'is_general' => 'required|boolean',
        ], [
            'name.required' => 'Mata Pelajaran wajib diisi.',
            'name.string' => 'Mata Pelajaran harus berupa teks.',
            'name.max' => 'Mata Pelajaran tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Mata Pelajaran sudah digunakan.',
            'is_general.required' => 'Status umum wajib dipilih.',
            'is_general.boolean' => 'Status umum harus bernilai benar atau salah.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $subject->update([
                'name' => $request->name,
                'major_id' => $request->is_general ? null : $request->major_id,
                'is_general' => $request->is_general,
            ]);

            DB::commit();

            return redirect()->route('admin.subjects.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui Mata Pelajaran.');
        }
    }

    public function destroy(Subject $subject)
    {
        if (
            $subject->grades()->exists() ||
            $subject->major()->exists() ||
            $subject->teachers()->exists()
        ) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus mata pelajaran yang sedang digunakan.');
        }

        DB::beginTransaction();

        try {
            $subject->delete();

            DB::commit();

            return redirect()->route('admin.subjects.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus Mata Pelajaran.');
        }
    }
}
