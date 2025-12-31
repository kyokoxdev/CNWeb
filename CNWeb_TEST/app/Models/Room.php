<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'room_number',
        'room_type',
        'price_per_night',
        'check_in_date',
        'check_out_date',
        'status'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
