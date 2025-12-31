<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_name',
        'email',
        'phone',
        'nationality',
        'id_number'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
