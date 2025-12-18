<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sale_id';

    protected $fillable = [
        'medicine_id',
        'quantity',
        'sale_date',
        'customer_phone'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'medicine_id');
    }
}
