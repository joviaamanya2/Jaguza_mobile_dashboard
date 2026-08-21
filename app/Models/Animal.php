<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    // ✅ ADD THIS - The TYPES constant
    const TYPES = [
        'cattle',
        'goat',
        'sheep',
        'pig',
        'poultry',
        'rabbit',
        'horse',
        'other'
    ];

    // ✅ ADD THIS
    const GENDERS = [
        'male',
        'female'
    ];

    // ✅ ADD THIS
    const HEALTH_STATUSES = [
        'healthy',
        'sick',
        'treatment',
        'quarantine',
        'recovering',
        'critical'
    ];

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

    // Helper method to get all types as array for dropdowns
    public static function getTypes()
    {
        return self::TYPES;
    }

    // Helper method to get all genders as array for dropdowns
    public static function getGenders()
    {
        return self::GENDERS;
    }

    // Helper method to get all health statuses as array for dropdowns
    public static function getHealthStatuses()
    {
        return self::HEALTH_STATUSES;
    }

    // Scope for active animals
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for animals by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope for healthy animals
    public function scopeHealthy($query)
    {
        return $query->where('health_status', 'healthy');
    }

    // Accessor for formatted weight
    public function getFormattedWeightAttribute()
    {
        return $this->weight ? number_format($this->weight, 2) . ' kg' : 'N/A';
    }

    // Accessor for formatted purchase price
    public function getFormattedPriceAttribute()
    {
        return $this->purchase_price ? 'UGX ' . number_format($this->purchase_price, 0) : 'N/A';
    }

    // Check if animal is available (active and healthy)
    public function isAvailable()
    {
        return $this->is_active && $this->health_status === 'healthy';
    }

    // Get age in years with months
    public function getAgeInYearsAndMonthsAttribute()
    {
        if (!$this->age) return 'N/A';
        
        $years = floor($this->age / 12);
        $months = $this->age % 12;
        
        if ($years > 0 && $months > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '') . " {$months} month" . ($months > 1 ? 's' : '');
        } elseif ($years > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '');
        } elseif ($months > 0) {
            return "{$months} month" . ($months > 1 ? 's' : '');
        }
        
        return 'Less than 1 month';
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        switch ($this->health_status) {
            case 'healthy':
                return 'success';
            case 'sick':
            case 'critical':
                return 'danger';
            case 'injured':
                return 'warning';
            case 'recovering':
                return 'info';
            case 'pregnant':
            case 'lactating':
                return 'primary';
            default:
                return 'secondary';
        }
    }

    // Get status badge label
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->health_status ?? 'unknown');
    }
}