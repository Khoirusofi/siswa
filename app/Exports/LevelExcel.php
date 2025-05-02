<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LevelExcel implements FromView
{
    public $rankedStudents;
    public $selectedLevel;
    public $selectedMajor;
    public $selectedYear;

    public function __construct($rankedStudents, $selectedLevel, $selectedMajor, $selectedYear)
    {
        $this->rankedStudents = $rankedStudents;
        $this->selectedLevel = $selectedLevel;
        $this->selectedMajor = $selectedMajor;
        $this->selectedYear = $selectedYear;
    }

    public function view(): View
    {
        return view('admin.rankings.levelexcel', [
            'rankedStudents' => $this->rankedStudents,
            'selectedLevel' => $this->selectedLevel,
            'selectedMajor' => $this->selectedMajor,
            'selectedYear' => $this->selectedYear
        ]);
    }
}
