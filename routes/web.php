<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardStudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\HistoryController;

Route::get('/', function () {
    return view('front.landing');
})->name('home');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin|teacher');

    Route::resource('years', YearController::class)->middleware('role:admin');

    Route::resource('majors', MajorController::class)->middleware('role:admin');

    Route::resource('levels', LevelController::class)->middleware('role:admin');

    Route::resource('rooms', RoomController::class)->middleware('role:admin');

    Route::get('rooms/{room:slug}/students/{yearSlug?}', [RoomController::class, 'students'])
        ->name('rooms.studentroom')
        ->middleware('role:admin');

    Route::resource('subjects', SubjectController::class)->middleware('role:admin');

    Route::get('grades', [GradeController::class, 'index'])
        ->name('grades.index')
        ->middleware('role:admin|teacher');

    Route::post('grades', [GradeController::class, 'store'])
        ->name('grades.store')
        ->middleware('role:admin|teacher');

    Route::get('characters', [CharacterController::class, 'index'])
        ->name('characters.index')
        ->middleware('role:admin|teacher');

    Route::post('characters', [CharacterController::class, 'store'])
        ->name('characters.store')
        ->middleware('role:admin|teacher');

    Route::get('achievements', [AchievementController::class, 'index'])
        ->name('achievements.index')
        ->middleware('role:admin|teacher');

    Route::post('achievements', [AchievementController::class, 'store'])
        ->name('achievements.store')
        ->middleware('role:admin|teacher');

    Route::get('attendances', [AttendanceController::class, 'index'])
        ->name('attendances.index')
        ->middleware('role:admin|teacher');

    Route::post('attendances', [AttendanceController::class, 'store'])
        ->name('attendances.store')
        ->middleware('role:admin|teacher');

    Route::get('extracurriculars', [extracurricularController::class, 'index'])
        ->name('extracurriculars.index')
        ->middleware('role:admin|teacher');

    Route::post('extracurriculars', [ExtracurricularController::class, 'store'])
        ->name('extracurriculars.store')
        ->middleware('role:admin|teacher');

    Route::get('rankings/room', [RankingController::class, 'perRoom'])
        ->name('rankings.room')
        ->middleware('role:admin|teacher');

    Route::get('rankings/level', [RankingController::class, 'perLevel'])
        ->name('rankings.level')
        ->middleware('role:admin|teacher');

    Route::get('rankings/roompdf', [RankingController::class, 'exportRoomPdf'])
        ->name('rankings.roompdf')
        ->middleware('role:admin|teacher');

    Route::get('rankings/levelpdf', [RankingController::class, 'exportLevelPdf'])
        ->name('rankings.levelpdf')
        ->middleware('role:admin|teacher');

    Route::get('rankings/roomexcel', [RankingController::class, 'exportRoomExcel'])
        ->name('rankings.roomexcel')
        ->middleware('role:admin|teacher');

    Route::get('rankings/levelexcel', [RankingController::class, 'exportLevelExcel'])
        ->name('rankings.levelexcel')
        ->middleware('role:admin|teacher');

    Route::resource('teachers', TeacherController::class)->middleware('role:admin');

    Route::resource('students', StudentController::class)->middleware('role:admin');

    Route::post('students/{student}/save-class', [StudentController::class, 'saveClass'])
        ->name('students.save-class')
        ->middleware('role:admin');
});

Route::prefix('student')->name('student.')->middleware(['auth'])->group(function () {

    Route::get('dashboard', [HistoryController::class, 'dashboard'])
        ->name('dashboard')
        ->middleware('role:student');

    Route::get('history', [HistoryController::class, 'history'])
        ->name('history')
        ->middleware('role:student');

    Route::get('history/historypdf', [HistoryController::class, 'exportHistory'])
        ->name('historypdf')
        ->middleware('role:student');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
