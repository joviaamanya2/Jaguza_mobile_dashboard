<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // This maps to the user who owns the farm
        'name',
        'owner_name',
        'location',
        'size',
        'description',
        'established_year',
        'coordinates',
        'facilities',
        'image',
        'is_active',
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
     // Relationship to the user who owns the farm
    

    // Relationship to the owner (if owner_id is different from user_id)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }

        return Storage::disk('public')->exists($this->image)
            ? Storage::disk('public')->url($this->image)
            : asset('storage/' . ltrim($this->image, '/'));
    }

    public function getTotalAnimalsAttribute()
    {
        return $this->animals()->count();
    }
}
