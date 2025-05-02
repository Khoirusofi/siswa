<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'major_id',
        'is_general',
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
