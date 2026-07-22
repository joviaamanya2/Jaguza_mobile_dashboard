<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SicknessReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'affected_animal_type',
        'affected_animal_count',
        'symptom_primary',
        'symptom_other',
        'symptom_duration',
        'severity_level',
        'status',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'affected_animal_count' => 'integer',
        'attachments' => 'array',
    ];

    const STATUS = ['open', 'treating', 'resolved', 'critical', 'referred'];
    const SEVERITY = ['mild', 'medium', 'severe', 'critical'];

    /**
     * The user who filed the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
