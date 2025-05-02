<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
