<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
