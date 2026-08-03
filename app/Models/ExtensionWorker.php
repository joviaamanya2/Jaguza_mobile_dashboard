<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtensionWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expertise_area',
        'education_level',
        'assigned_region',
        'phone_number',
        'years_of_experience',
        'languages_spoken',
        'is_available',
        'rating',
        'total_farm_visits',
        'bio',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'rating' => 'decimal:2',
        'total_farm_visits' => 'integer',
        'years_of_experience' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeTopRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function getFullNameAttribute()
    {
        return $this->user->name ?? 'Unknown';
    }

    public function getInitialsAttribute()
    {
        $name = $this->user->name ?? '';
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        return substr($initials, 0, 2);
    }
}

