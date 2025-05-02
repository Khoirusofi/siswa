<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Student;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $students = Student::query()
            ->with(['user', 'enrollments.room', 'enrollments.year', 'enrollments.room.major'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $years = Year::all();
        $rooms = Room::all();

        return view('admin.students.edit', compact(
            'years',
            'rooms'
        ));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal terdiri dari 3 karakter.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'birth_date.date' => 'Format tanggal lahir tidak valid.',

            'gender.in' => 'Jenis kelamin harus diisi dengan Laki-laki atau Perempuan.',
            'address.min' => 'Alamat minimal terdiri dari 5 karakter.',
            'address.max' => 'Alamat tidak boleh lebih dari 255 karakter.',

            'phone_number.min' => 'Nomor telepon minimal terdiri dari 8 karakter.',
            'phone_number.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',

            'nisn.required' => 'NISN wajib diisi.',
            'nisn.min' => 'NISN minimal terdiri dari 10 karakter.',
            'nisn.max' => 'NISN tidak boleh lebih dari 10 karakter.',
            'nisn.unique' => 'NISN sudah digunakan.',

            'nis.required' => 'NIS wajib diisi.',
            'nis.min' => 'NIS minimal terdiri dari 8 karakter.',
            'nis.max' => 'NIS tidak boleh lebih dari 12 karakter.',
            'nis.unique' => 'NIS sudah digunakan.',

            'parent.min' => 'Nama orang tua minimal terdiri dari 3 karakter.',
            'parent.max' => 'Nama orang tua tidak boleh lebih dari 255 karakter.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'address' => 'nullable|string|min:5|max:255',
            'phone_number' => 'nullable|string|min:8|max:20',
            'nisn' => 'required|string|min:10|max:10|unique:students,nisn',
            'nis' => 'required|string|min:8|max:12|unique:students,nis',
            'parent' => 'nullable|string|min:3|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $nisn = $request->nisn;
            $email = "siswa#{$nisn}@gmail.com";
            $password = "siswa#{$nisn}";

            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'password' => Hash::make($password),
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
            ]);

            $user->assignRole('student');

            Student::create([
                'user_id' => $user->id,
                'nisn' => $nisn,
                'nis' => $request->nis,
                'parent' => $request->parent,
            ]);

            DB::commit();

            return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data siswa.');
        }
    }

    public function edit(Student $student)
    {
        $years = Year::all();
        $rooms = Room::all();
        $enrollmentToEdit = $student->enrollments()->latest()->first();

        return view('admin.students.edit', compact(
            'student',
            'years',
            'rooms',
            'enrollmentToEdit'
        ));
    }

    public function update(Request $request, Student $student)
    {
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal terdiri dari 3 karakter.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'birth_date.date' => 'Format tanggal lahir tidak valid.',

            'gender.in' => 'Jenis kelamin harus diisi dengan Laki-laki atau Perempuan.',

            'address.min' => 'Alamat minimal terdiri dari 5 karakter.',
            'address.max' => 'Alamat tidak boleh lebih dari 255 karakter.',

            'phone_number.min' => 'Nomor telepon minimal terdiri dari 8 karakter.',
            'phone_number.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',

            'nisn.required' => 'NISN wajib diisi.',
            'nisn.min' => 'NISN minimal terdiri dari 10 karakter.',
            'nisn.max' => 'NISN tidak boleh lebih dari 10 karakter.',
            'nisn.unique' => 'NISN sudah digunakan.',

            'nis.required' => 'NIS wajib diisi.',
            'nis.min' => 'NIS minimal terdiri dari 8 karakter.',
            'nis.max' => 'NIS tidak boleh lebih dari 12 karakter.',
            'nis.unique' => 'NIS sudah digunakan.',

            'parent.min' => 'Nama orang tua minimal terdiri dari 3 karakter.',
            'parent.max' => 'Nama orang tua tidak boleh lebih dari 255 karakter.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'address' => 'nullable|string|min:5|max:255',
            'phone_number' => 'nullable|string|min:8|max:20',
            'nisn' => 'required|string|min:10|max:10|unique:students,nisn,' . $student->id,
            'nis' => 'required|string|min:8|max:12|unique:students,nis,' . $student->id,
            'parent' => 'nullable|string|min:3|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $nisn = $request->nisn;
            $email = "siswa#{$nisn}@gmail.com";

            if ($student->user) {
                $userUpdate = [
                    'name' => $request->name,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                ];

                if ($nisn !== $student->nisn) {
                    $userUpdate['email'] = $email;
                    $userUpdate['password'] = Hash::make("siswa#{$nisn}");
                }

                $student->user->update($userUpdate);
            }

            $student->update([
                'nisn' => $nisn,
                'nis' => $request->nis,
                'parent' => $request->parent,
            ]);

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy(Student $student)
    {
        try {
            if (
                // $student->enrollments()->exists() ||
                // $student->grades()->exists() ||
                // $student->components()->exists() ||
                !is_null($student->user_id)
            ) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus siswa yang sedang digunakan.');
            }

            DB::beginTransaction();

            if ($student->user) {
                $student->user->delete();
            }

            $student->delete();

            DB::commit();

            return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus siswa.');
        }
    }

    public function saveClass(Request $request, Student $student)
    {
        $action = $request->input('action');

        DB::beginTransaction();

        try {
            if ($action === 'tambah') {
                $validated = $request->validate([
                    'year_id' => 'required|exists:years,id',
                    'room_id' => 'required|exists:rooms,id',
                ]);

                if ($student->enrollments()->where('year_id', $request->year_id)
                    ->where('room_id', $request->room_id)
                    ->exists()
                ) {
                    DB::rollBack();
                    return back()->with('error', 'Siswa sudah terdaftar di kelas ini.');
                }

                $student->enrollments()->create([
                    'year_id' => $request->year_id,
                    'room_id' => $request->room_id,
                ]);

                DB::commit();

                return redirect()->route('admin.students.edit', $student->id)
                    ->with('success', 'Kelas berhasil ditambahkan.');
            }

            if ($action === 'edit') {
                $validated = $request->validate([
                    'edit_enrollment_id' => 'required|exists:enrollments,id',
                    'edit_year_id' => 'required|exists:years,id',
                    'edit_room_id' => 'required|exists:rooms,id',
                ]);

                $enrollment = $student->enrollments()
                    ->where('id', $request->edit_enrollment_id)
                    ->first();

                if (!$enrollment) {
                    DB::rollBack();
                    return back()->with('error', 'Data kelas siswa tidak ditemukan.');
                }

                $enrollment->update([
                    'year_id' => $request->edit_year_id,
                    'room_id' => $request->edit_room_id,
                ]);

                DB::commit();

                return redirect()->route('admin.students.edit', $student->id)
                    ->with('success', 'Kelas siswa berhasil diubah.');
            }

            DB::rollBack();
            return back()->with('error', 'Aksi tidak dikenali.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
