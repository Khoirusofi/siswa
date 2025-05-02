<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Room;
use App\Models\Year;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::with('major')->get();
        $years = Year::all();

        $selectedRoomSlug = $request->room;
        $selectedYearSlug = $request->year;

        $selectedRoom = null;
        $selectedYear = null;
        $enrollments = collect();
        $existingGrades = collect();
        $subjects = collect(); // Akan diisi hanya jika room & year dipilih

        if ($selectedRoomSlug && $selectedYearSlug) {
            $selectedRoom = Room::where('slug', $selectedRoomSlug)->first();
            $selectedYear = Year::where('slug', $selectedYearSlug)->first();

            if ($selectedRoom && $selectedYear) {
                $enrollments = Enrollment::where('room_id', $selectedRoom->id)
                    ->where('year_id', $selectedYear->id)
                    ->with('student.user')
                    ->get();

                // Ambil hanya subject yang umum atau sesuai jurusan kelas
                $subjects = Subject::where(function ($query) use ($selectedRoom) {
                    $query->where('is_general', true) // Umum
                        ->orWhere('major_id', $selectedRoom->major_id); // Khusus jurusan
                })->get();

                // Ambil nilai yang sudah ada
                $existingGrades = Grade::whereIn('enrollment_id', $enrollments->pluck('id'))
                    ->get()
                    ->groupBy(function ($grade) {
                        return $grade->enrollment_id . '-' . $grade->subject_id;
                    });
            }
        }

        return view('admin.grades.index', compact(
            'rooms',
            'years',
            'subjects',
            'enrollments',
            'existingGrades',
            'selectedRoomSlug',
            'selectedYearSlug',
            'selectedRoom',
            'selectedYear'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|exists:enrollments,id',
            'grades.*.subjects' => 'required|array',
            'grades.*.subjects.*' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->grades as $gradeData) {
                $enrollmentId = $gradeData['enrollment_id'];
                $enrollment = Enrollment::with('room')->findOrFail($enrollmentId);
                $studentMajor = $enrollment->room->major_id;

                foreach ($gradeData['subjects'] as $subjectId => $score) {
                    $subject = Subject::findOrFail($subjectId);

                    // Validasi subject: hanya boleh jika subject umum atau sesuai major
                    $isSubjectValid = $subject->is_general || $subject->major_id === $studentMajor;

                    if (!$isSubjectValid) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Subject tidak sesuai dengan major siswa.');
                    }

                    if ($score !== null && $score !== '') {
                        Grade::updateOrCreate(
                            [
                                'enrollment_id' => $enrollmentId,
                                'subject_id' => $subjectId,
                            ],
                            [
                                'score' => $score,
                            ]
                        );
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan nilai.');
        }
    }
}
