<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RoomExcel implements FromView
{
    public $rankedStudents;
    public $selectedRoom;
    public $selectedYear;

    public function __construct($rankedStudents, $selectedRoom, $selectedYear)
    {
        $this->rankedStudents = $rankedStudents;
        $this->selectedRoom = $selectedRoom;
        $this->selectedYear = $selectedYear;
    }

    public function view(): View
    {
        return view('admin.rankings.roomexcel', [
            'rankedStudents' => $this->rankedStudents,
            'selectedRoom' => $this->selectedRoom,
            'selectedYear' => $this->selectedYear
        ]);
    }
}
