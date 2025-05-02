<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'enrollment_id',
        'character',
        'achievement',
        'attendance',
        'extracurricular'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
