<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Year;
use App\Models\Enrollment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function exportHistory(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $selectedSlug = $request->input('history');

        if (!$selectedSlug || !str_contains($selectedSlug, '%')) {
            return back()->with('error', 'Data tidak valid.');
        }

        [$roomSlug, $yearSlug] = explode('%', $selectedSlug);

        $selectedRoom = Room::where('slug', $roomSlug)->first();
        $selectedYear = Year::where('slug', $yearSlug)->first();

        if (!$selectedRoom || !$selectedYear) {
            return back()->with('error', 'Kelas atau tahun ajaran tidak ditemukan.');
        }

        $enrollments = Enrollment::with(['grades', 'components', 'student'])
            ->where('room_id', $selectedRoom->id)
            ->where('year_id', $selectedYear->id)
            ->get();

        $studentCount = $enrollments->count();

        $scoreList = collect();

        foreach ($enrollments as $enroll) {
            $grades = $enroll->grades;
            $components = $enroll->components->first();
            $totalScore = $this->calculateTotalScore($grades, $components);

            $scoreList->push([
                'student_id' => $enroll->student_id,
                'total_score' => $totalScore,
            ]);
        }

        $sortedScores = $scoreList->sortByDesc('total_score')->values();

        $ranking = $sortedScores->search(function ($item) use ($student) {
            return $item['student_id'] === $student->id;
        }) + 1;

        $myEnrollment = $enrollments->firstWhere('student_id', $student->id);

        if (!$myEnrollment) {
            return back()->with('error', 'Data nilai tidak ditemukan.');
        }

        $grades = $myEnrollment->grades;
        $components = $myEnrollment->components->first();
        $totalScore = $this->calculateTotalScore($grades, $components);

        $pdf = Pdf::loadView('students.historypdf', [
            'student' => $student,
            'room' => $selectedRoom,
            'year' => $selectedYear,
            'grades' => $grades,
            'components' => $components,
            'totalScore' => $totalScore,
            'ranking' => $ranking,
            'studentCount' => $studentCount,
        ]);

        return $pdf->download(
            'riwayat-nilai-' .
                Str::slug($student->user->name) . '-' .
                Str::slug($selectedRoom->name) . '-' .
                Str::slug($selectedYear->name) . '.pdf'
        );
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        // Ambil semua riwayat kelas (room + year) siswa
        $histories = Enrollment::with(['room', 'year'])
            ->where('student_id', $student->id)
            ->get()
            ->map(function ($enroll) {
                return [
                    'room_id' => $enroll->room_id,
                    'year_id' => $enroll->year_id,
                    'label' =>  $enroll->room->name . ' -' . ' ' . $enroll->year->name . '',
                    'slug' => $enroll->room->slug . '%' . $enroll->year->slug,
                ];
            })
            ->unique('slug')
            ->values();

        // Inisialisasi variabel
        $selectedRoom = null;
        $selectedYear = null;
        $grades = collect();
        $components = null;
        $totalScore = null;
        $ranking = null;
        $studentCount = 0;

        // Ambil request yang dikirim dari dropdown kombinasi
        $selectedSlug = $request->input('history');

        if ($selectedSlug && str_contains($selectedSlug, '%')) {
            [$roomSlug, $yearSlug] = explode('%', $selectedSlug);

            $selectedRoom = Room::where('slug', $roomSlug)->first();
            $selectedYear = Year::where('slug', $yearSlug)->first();
        }

        if ($selectedRoom && $selectedYear && $student) {
            // Ambil semua enrollment di kelas dan tahun ajaran tersebut
            $enrollments = Enrollment::with(['grades', 'components', 'student'])
                ->where('room_id', $selectedRoom->id)
                ->where('year_id', $selectedYear->id)
                ->get();

            $studentCount = $enrollments->count();

            // Hitung total skor untuk masing-masing siswa
            $scoreList = collect();

            foreach ($enrollments as $enroll) {
                $grades = $enroll->grades;
                $components = $enroll->components->first();
                $totalScore = $this->calculateTotalScore($grades, $components);

                $scoreList->push([
                    'student_id' => $enroll->student_id,
                    'total_score' => $totalScore,
                ]);
            }

            // Urutkan berdasarkan skor tertinggi
            $sortedScores = $scoreList->sortByDesc('total_score')->values();

            // Cari ranking siswa yang sedang login
            $ranking = $sortedScores->search(function ($item) use ($student) {
                return $item['student_id'] === $student->id;
            }) + 1;

            // Ambil ulang data nilai & komponen untuk siswa yang login
            $myEnrollment = $enrollments->firstWhere('student_id', $student->id);

            if ($myEnrollment) {
                $grades = $myEnrollment->grades;
                $components = $myEnrollment->components->first();
                $totalScore = $this->calculateTotalScore($grades, $components);
            }
        }

        return view('students.history', compact(
            'histories',
            'selectedRoom',
            'selectedYear',
            'grades',
            'components',
            'totalScore',
            'ranking',
            'studentCount'
        ));
    }

    private function calculateTotalScore($grades, $components)
    {
        $averageGrade = $grades->avg('score') ?? 0;

        $w1 = 0.20;
        $w2 = 0.10;
        $w3 = 0.10;
        $w4 = 0.40;
        $w5 = 0.20;

        $x1 = $averageGrade * $w1;
        $x2 = $components ? $components->character * $w2 : 0;
        $x3 = $components ? $components->achievement * $w3 : 0;
        $x4 = $components ? $components->attendance * $w4 : 0;
        $x5 = $components ? $components->extracurricular * $w5 : 0;

        return round($x1 + $x2 + $x3 + $x4 + $x5, 2);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;

        // Ambil semua enrollment siswa beserta grades, components, room, dan year
        $enrollments = Enrollment::with(['grades', 'components', 'room', 'year'])
            ->where('student_id', $student->id)
            ->get();

        // Menyiapkan data untuk visualisasi progres dengan total score
        $progressData = $enrollments->map(function ($enroll) {
            $grades = $enroll->grades;
            $components = $enroll->components->first();
            $totalScore = $this->calculateTotalScore($grades, $components);

            return [
                'label' => $enroll->room->name . ' - ' . $enroll->year->name,
                'score' => $totalScore
            ];
        });

        return view('students.dashboard', compact(
            'student',
            'enrollments',
            'progressData',
        ));
    }
}
