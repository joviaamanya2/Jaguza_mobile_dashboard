<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'role',
        'phone',
        'email',
        'date_joined',
        'is_active',
    ];

    protected $casts = [
        'date_joined' => 'date',
        'is_active' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
