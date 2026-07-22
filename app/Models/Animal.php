<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification_number',
        'name',
        'type',
        'breed',
        'gender',
        'age',
        'weight',
        'health_status',
        'farm_id',
        'owner_id',
        'photo',
        'date_bought',
        'purchase_price',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'date_bought' => 'date',
        'is_active' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}