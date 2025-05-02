<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Year;
use App\Models\Enrollment;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
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
        $existingAchievementData = collect();

        if ($selectedRoomSlug && $selectedYearSlug) {
            $selectedRoom = Room::where('slug', $selectedRoomSlug)->first();
            $selectedYear = Year::where('slug', $selectedYearSlug)->first();

            if ($selectedRoom && $selectedYear) {
                $enrollments = Enrollment::where('room_id', $selectedRoom->id)
                    ->where('year_id', $selectedYear->id)
                    ->with('student.user')
                    ->get();

                $existingAchievementData = Component::whereIn('enrollment_id', $enrollments->pluck('id'))
                    ->get()
                    ->pluck('achievement', 'enrollment_id');
            }
        }

        return view('admin.achievements.index', compact(
            'rooms',
            'years',
            'enrollments',
            'existingAchievementData',
            'selectedRoomSlug',
            'selectedYearSlug',
            'selectedRoom',
            'selectedYear'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'components' => 'required|array',
            'components.*.enrollment_id' => 'required|exists:enrollments,id',
            'components.*.achievement' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->components as $componentData) {
                $enrollmentId = $componentData['enrollment_id'];
                $enrollment = Enrollment::findOrFail($enrollmentId);

                Component::updateOrCreate(
                    ['enrollment_id' => $enrollmentId],
                    ['achievement' => $componentData['achievement'] ?? null]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Nilai prestasi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan nilai prestasi.');
        }
    }
}
