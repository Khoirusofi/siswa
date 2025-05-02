<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Year;
use App\Models\Enrollment;

class DashboardStudentController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $rooms = Room::whereHas('enrollments', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->get();

        $years = Year::whereHas('enrollments', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->get();

        return view('students.dashboard', compact('rooms', 'years'));
    }

    public function showScores(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'year_id' => 'required|exists:years,id',
        ]);

        $room = Room::findOrFail($request->room_id);
        $year = Year::findOrFail($request->year_id);
        $student = Auth::user()->student;

        $enrollments = $this->getEnrollmentsByRoom($room, $year);
        $rankings = $this->calculateRankings($enrollments);

        $myRanking = collect($rankings)->firstWhere('student.id', $student->id);

        return response()->json([
            'my_score' => $myRanking,
            'chart_data' => $this->prepareChartData($myRanking),
        ]);
    }

    private function getEnrollmentsByRoom($room, $year)
    {
        return Enrollment::with(['student.user', 'grades.subject', 'components'])
            ->where('room_id', $room->id)
            ->where('year_id', $year->id)
            ->get();
    }

    private function calculateRankings($enrollments)
    {
        $results = [];

        foreach ($enrollments as $enrollment) {
            $grades = $enrollment->grades;
            $component = $enrollment->components->first();
            $student = $enrollment->student;

            $averageGrade = $grades->avg('score') ?? 0;

            if ($component) {
                $w1 = 0.20;
                $w2 = 0.10;
                $w3 = 0.10;
                $w4 = 0.40;
                $w5 = 0.20;

                $x1 = $averageGrade * $w1;
                $x2 = $component->character * $w2;
                $x3 = $component->achievement * $w3;
                $x4 = $component->attendance * $w4;
                $x5 = $component->extracurricular * $w5;

                $totalScore = $x1 + $x2 + $x3 + $x4 + $x5;

                // Ambil semua nilai mata pelajaran
                $subjectGrades = $grades->map(function ($grade) {
                    return [
                        'subject' => $grade->subject->name ?? 'Mata Pelajaran',
                        'score' => round($grade->score, 2),
                    ];
                });

                $results[] = [
                    'student'         => $student,
                    'enrollment'      => $enrollment,
                    'score'           => round($totalScore, 2),
                    'grades'          => round($x1, 2),
                    'character'       => round($x2, 2),
                    'achievement'     => round($x3, 2),
                    'attendance'      => round($x4, 2),
                    'extracurricular' => round($x5, 2),
                    'subject_grades'  => $subjectGrades,
                ];
            }
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return $results;
    }

    private function prepareChartData($ranking)
    {
        if (!$ranking) {
            return null;
        }

        return [
            'labels' => ['Akademik', 'Akhlak', 'Prestasi', 'Absen', 'Eskul'],
            'datasets' => [
                [
                    'label' => 'Nilai Komponen',
                    'backgroundColor' => ['#6366f1', '#ec4899', '#f59e0b', '#3b82f6', '#10b981'],
                    'data' => [
                        $ranking['grades'],
                        $ranking['character'],
                        $ranking['achievement'],
                        $ranking['attendance'],
                        $ranking['extracurricular'],
                    ],
                ],
            ],
        ];
    }
}
