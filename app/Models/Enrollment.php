<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'year_id',
        'room_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }
}
