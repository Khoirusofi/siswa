<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Room;
use App\Models\Grade;
use App\Models\Component;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalSubjects = Subject::count();
        $totalRooms = Room::count();

        // Menampilkan 5 siswa terbaik berdasarkan nilai
        $topStudents = Student::with(['enrollments.grades', 'enrollments.components'])
            ->get()
            ->flatMap(function ($student) {
                return $student->enrollments->map(function ($enrollment) use ($student) {
                    $grades = $enrollment->grades;
                    $component = $enrollment->components->first();

                    $averageGrade = $grades->avg('score') ?? 0;

                    if (!$component) {
                        return null;
                    }

                    // Bobot komponen
                    $w1 = 0.20;
                    $w2 = 0.10;
                    $w3 = 0.10;
                    $w4 = 0.40;
                    $w5 = 0.20;

                    // Menghitung kontribusi tiap komponen
                    $x1 = $averageGrade * $w1;
                    $x2 = $component->character * $w2;
                    $x3 = $component->achievement * $w3;
                    $x4 = $component->attendance * $w4;
                    $x5 = $component->extracurricular * $w5;

                    // Menghitung total skor
                    $totalScore = $x1 + $x2 + $x3 + $x4 + $x5;

                    return [
                        'student'         => $student,
                        'enrollment'      => $enrollment,
                        'score'           => round($totalScore, 2),
                        'grades'          => round($x1, 2),
                        'character'       => round($x2, 2),
                        'achievement'     => round($x3, 2),
                        'attendance'      => round($x4, 2),
                        'extracurricular' => round($x5, 2),
                    ];
                })->filter(); // Hapus null (jika tidak ada komponen)
            })
            ->sortByDesc('score') // Urutkan dari yang paling tinggi
            ->take(5); // Ambil 5 skor tertinggi


        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalSubjects',
            'totalRooms',
            'topStudents'
        ));
    }
}
