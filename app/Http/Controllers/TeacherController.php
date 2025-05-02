<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $teachers = Teacher::query()->with(['user', 'subject'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::pluck('name', 'id');

        return view('admin.teachers.edit', compact(
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        $messages = [
            'nip.required' => 'NIP wajib diisi.',
            'nip.string' => 'NIP harus berupa teks.',
            'nip.min' => 'NIP minimal 8 karakter.',
            'nip.max' => 'NIP maksimal 20 karakter.',
            'nip.unique' => 'NIP sudah digunakan.',

            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',

            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',

            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.string' => 'Jenis kelamin harus berupa teks.',

            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.min' => 'Alamat minimal 3 karakter.',

            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.string' => 'Nomor telepon harus berupa teks.',
            'phone_number.min' => 'Nomor telepon minimal 3 karakter.',
        ];

        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|min:8|max:20|unique:teachers,nip',
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|min:3|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|min:3|string',
            'phone_number' => 'required|min:3|string',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $nip = $request->nip;
            $email = "guru#{$nip}@gmail.com";
            $password = "guru#{$nip}";

            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'password' => Hash::make($password),
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
            ]);

            $user->assignRole('teacher');

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'subject_id' => $request->subject_id,
            ]);

            DB::commit();

            return redirect()->route('admin.teachers.index')->with('success', "Guru berhasil ditambahkan.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }


    public function edit(Teacher $teacher)
    {
        $subjects = Subject::pluck('name', 'id');

        return view('admin.teachers.edit', compact(
            'teacher',
            'subjects'
        ));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $messages = [
            'nip.required' => 'NIP wajib diisi.',
            'nip.string' => 'NIP harus berupa teks.',
            'nip.min' => 'NIP minimal 8 karakter.',
            'nip.max' => 'NIP maksimal 20 karakter.',
            'nip.unique' => 'NIP sudah digunakan.',

            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',

            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',

            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.string' => 'Jenis kelamin harus berupa teks.',

            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.min' => 'Alamat minimal 3 karakter.',

            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.string' => 'Nomor telepon harus berupa teks.',
            'phone_number.min' => 'Nomor telepon minimal 3 karakter.',
        ];

        $validated = $request->validate([
            'nip' => 'required|string|min:8|max:20|unique:teachers,nip,' . $teacher->id,
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|min:3|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|min:3|string',
            'phone_number' => 'required|min:3|string',
        ], $messages);

        DB::beginTransaction();

        try {
            $nip = $request->nip;
            $email = "guru#{$nip}@gmail.com";

            if ($teacher->user) {
                $userUpdate = [
                    'name' => $request->name,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                ];

                if ($nip !== $teacher->nip) {
                    $userUpdate['email'] = $email;
                    $userUpdate['password'] = Hash::make("guru#{$nip}");
                }

                $teacher->user->update($userUpdate);
            }

            $teacher->update([
                'nip' => $nip,
                'subject_id' => $request->subject_id,
            ]);

            DB::commit();

            return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data.'])->withInput();
        }
    }


    public function destroy(Teacher $teacher)
    {
        try {
            if ($teacher->room()->exists()) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus guru yang sedang digunakan.');
            }

            DB::beginTransaction();

            if ($teacher->user) {
                $teacher->user->delete();
            }

            $teacher->delete();

            DB::commit();

            return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data guru.');
        }
    }
}
