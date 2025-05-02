<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Year;
use Illuminate\Support\Facades\DB;
use App\Models\Level;
use App\Models\Major;
use App\Models\Teacher;
use App\Models\Enrollment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $rooms = Room::query()->with(['level', 'major', 'teacher'])
            ->when(
                $search,
                fn($query) =>
                $query->where('name', 'like', '%' . $search . '%')
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $levels = Level::all();
        $majors = Major::all();
        $teachers = Teacher::all();

        return view('admin.rooms.edit', compact(
            'levels',
            'majors',
            'teachers'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:rooms,name',
            'level_id' => 'required|exists:levels,id',
            'major_id' => 'required|exists:majors,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ], [
            'name.required' => 'Kelas wajib diisi.',
            'name.unique' => 'Kelas sudah digunakan.',
            'level_id.required' => 'Tingkat kelas wajib dipilih.',
            'major_id.required' => 'Jurusan wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Room::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'level_id' => $request->level_id,
                'major_id' => $request->major_id,
                'teacher_id' => $request->teacher_id,
            ]);

            DB::commit();

            return redirect()->route('admin.rooms.index')->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data kelas.');
        }
    }

    public function edit(Room $room)
    {
        $levels = Level::all();
        $majors = Major::all();
        $teachers = Teacher::all();

        return view('admin.rooms.edit', compact(
            'room',
            'levels',
            'majors',
            'teachers'
        ));
    }

    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:rooms,name,' . $room->id,
            'level_id' => 'required|exists:levels,id',
            'major_id' => 'required|exists:majors,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ], [
            'name.required' => 'Kelas wajib diisi.',
            'name.unique' => 'Kelas sudah digunakan.',
            'level_id.required' => 'Tingkat kelas wajib dipilih.',
            'major_id.required' => 'Jurusan wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $room->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'level_id' => $request->level_id,
                'major_id' => $request->major_id,
                'teacher_id' => $request->teacher_id,
            ]);

            DB::commit();

            return redirect()->route('admin.rooms.index')->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data kelas.');
        }
    }

    public function destroy(Room $room)
    {
        if ($room->enrollments()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kelas yang sedang digunakan.');
        }

        DB::beginTransaction();

        try {
            $room->delete();

            DB::commit();

            return redirect()->route('admin.rooms.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kelas.');
        }
    }

    public function students($slug, $yearSlug = null)
    {
        $search = trim(request('search'));

        $room = Room::where('slug', $slug)->firstOrFail();

        $years = Year::orderBy('created_at', 'desc')->get();

        $selectedYear = null;
        if ($yearSlug) {
            $selectedYear = Year::where('slug', $yearSlug)->first();
        }

        $students = collect();

        if ($selectedYear) {
            $enrollments = Enrollment::with('student.user')
                ->where('room_id', $room->id)
                ->where('year_id', $selectedYear->id)
                ->when($search, function ($query) use ($search) {
                    $query->whereHas('student.user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();

            $students = $enrollments;
        }

        return view('admin.rooms.studentroom', compact('room', 'years', 'students', 'selectedYear'));
    }
}
