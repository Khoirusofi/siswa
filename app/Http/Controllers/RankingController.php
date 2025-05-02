<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Year;
use App\Models\Level;
use App\Models\Major;
use App\Models\Enrollment;
use App\Exports\RoomExcel;
use App\Exports\LevelExcel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class RankingController extends Controller
{
    public function exportRoomPdf(Request $request)
    {
        $room = Room::where('slug', $request->room)->first();
        $year = Year::where('slug', $request->year)->first();

        if (!$room || !$year) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $enrollments = $this->getEnrollmentsByRoom($room, $year);
        $rankedStudents = $this->calculateRankings($enrollments);

        if (empty($rankedStudents)) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $pdf = PDF::loadView('admin.rankings.roompdf', [
            'rankedStudents' => $rankedStudents,
            'selectedRoom' => $room->name,
            'selectedYear' => $year->name,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rankings-' . Str::slug($room->name) . '-' . Str::slug($year->name) . '.pdf');
    }

    public function exportLevelPdf(Request $request)
    {
        $level = Level::where('slug', $request->level)->first();
        $major = Major::where('slug', $request->major)->first();
        $year  = Year::where('slug', $request->year)->first();

        if (!$level || !$major || !$year) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $enrollments = $this->getEnrollmentsByLevel($level, $major, $year);
        $rankedStudents = $this->calculateRankings($enrollments);

        if (empty($rankedStudents)) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $pdf = PDF::loadView('admin.rankings.levelpdf', [
            'rankedStudents' => $rankedStudents,
            'selectedLevel' => $level->name,
            'selectedMajor' => $major->name,
            'selectedYear' => $year->name,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rankings-' . Str::slug($level->name) . '-' . Str::slug($major->name) . '-' . Str::slug($year->name) . '.pdf');
    }

    public function exportRoomExcel(Request $request)
    {
        $room = Room::where('slug', $request->room)->first();
        $year = Year::where('slug', $request->year)->first();

        if (!$room || !$year) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $rankedStudents = $this->calculateRankings(
            $this->getEnrollmentsByRoom($room, $year)
        );

        return Excel::download(
            new RoomExcel($rankedStudents, $room, $year),
            'ranking-' . $room->slug . '-' . $year->slug . '.xlsx'
        );
    }

    public function exportLevelExcel(Request $request)
    {
        $level = Level::where('slug', $request->level)->first();
        $major = Major::where('slug', $request->major)->first();
        $year  = Year::where('slug', $request->year)->first();

        if (!$level || !$major || !$year) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $rankedStudents = $this->calculateRankings(
            $this->getEnrollmentsByLevel($level, $major, $year)
        );

        return Excel::download(
            new LevelExcel($rankedStudents, $level->name, $major->name, $year->name),
            'ranking-' . $level->slug . '-' . $major->slug . '-' . $year->slug . '.xlsx'
        );
    }

    public function perRoom(Request $request)
    {
        $selectedRoom = Room::where('slug', $request->room)->first();
        $selectedYear = Year::where('slug', $request->year)->first();

        $enrollmentsQuery = Enrollment::query()->with(['room', 'year']);

        if ($selectedRoom) {
            $enrollmentsQuery->where('room_id', $selectedRoom->id);
        }

        if ($selectedYear) {
            $enrollmentsQuery->where('year_id', $selectedYear->id);
        }

        $enrollments = $enrollmentsQuery->get();

        $rooms = Room::whereIn('id', $enrollments->pluck('room_id')->unique())->get();
        $years = Year::whereIn('id', $enrollments->pluck('year_id')->unique())->get();

        $rankedStudents = [];

        if ($selectedRoom && $selectedYear) {
            $enrollments = $this->getEnrollmentsByRoom($selectedRoom, $selectedYear);
            $rankedStudents = $this->calculateRankings($enrollments);

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $pagedData = array_slice($rankedStudents, ($currentPage - 1) * $perPage, $perPage);

            $rankedStudents = new LengthAwarePaginator($pagedData, count($rankedStudents), $perPage, $currentPage, [
                'path' => request()->url(),
                'query' => request()->query()
            ]);
        } else {
            $rankedStudents = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.rankings.room', compact(
            'rooms',
            'years',
            'rankedStudents',
            'selectedRoom',
            'selectedYear'
        ));
    }

    public function perLevel(Request $request)
    {
        $selectedLevel = Level::where('slug', $request->level)->first();
        $selectedMajor = Major::where('slug', $request->major)->first();
        $selectedYear  = Year::where('slug', $request->year)->first();

        $enrollmentsQuery = Enrollment::query()
            ->with(['room.level', 'room.major', 'year']);

        if ($selectedLevel) {
            $enrollmentsQuery->whereHas('room', function ($query) use ($selectedLevel) {
                $query->where('level_id', $selectedLevel->id);
            });
        }

        if ($selectedMajor) {
            $enrollmentsQuery->whereHas('room', function ($query) use ($selectedMajor) {
                $query->where('major_id', $selectedMajor->id);
            });
        }

        if ($selectedYear) {
            $enrollmentsQuery->where('year_id', $selectedYear->id);
        }

        $enrollments = $enrollmentsQuery->get();

        $levels = Level::whereIn('id', $enrollments->pluck('room.level_id')->unique())->get();
        $majors = Major::whereIn('id', $enrollments->pluck('room.major_id')->unique())->get();
        $years  = Year::whereIn('id', $enrollments->pluck('year_id')->unique())->get();

        $rankedStudents = [];
        if ($selectedLevel && $selectedMajor && $selectedYear) {
            $filteredEnrollments = $this->getEnrollmentsByLevel($selectedLevel, $selectedMajor, $selectedYear);
            $rankedStudents = $this->calculateRankings($filteredEnrollments);

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $pagedData = array_slice($rankedStudents, ($currentPage - 1) * $perPage, $perPage);

            $rankedStudents = new LengthAwarePaginator($pagedData, count($rankedStudents), $perPage, $currentPage, [
                'path' => request()->url(),
                'query' => request()->query()
            ]);
        } else {
            $rankedStudents = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.rankings.level', compact(
            'levels',
            'majors',
            'years',
            'selectedLevel',
            'selectedMajor',
            'selectedYear',
            'rankedStudents'
        ));
    }

    private function getEnrollmentsByRoom($room, $year)
    {
        return Enrollment::with(['student.user', 'grades', 'components'])
            ->where('room_id', $room->id)
            ->where('year_id', $year->id)
            ->get();
    }

    private function getEnrollmentsByLevel($level, $major, $year)
    {
        $roomIds = Room::where('level_id', $level->id)
            ->where('major_id', $major->id)
            ->pluck('id');

        return Enrollment::with(['student.user', 'grades', 'components', 'room'])
            ->whereIn('room_id', $roomIds)
            ->where('year_id', $year->id)
            ->get();
    }

    // PENERAPAN METODE REGRESI LINEAR
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

                $results[] = [
                    'student'         => $student,
                    'enrollment'      => $enrollment,
                    'score'           => round($totalScore, 2),
                    'grades'          => round($x1, 2),
                    'character'       => round($x2, 2),
                    'achievement'     => round($x3, 2),
                    'attendance'      => round($x4, 2),
                    'extracurricular' => round($x5, 2),
                ];
            }
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return $results;
    }
}
