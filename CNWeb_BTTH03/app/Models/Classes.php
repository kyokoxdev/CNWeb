<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = "classes";
    protected $primaryKey = "id";

    protected $fillable = [
        'grade_level',
        'room_number'
    ];
}
