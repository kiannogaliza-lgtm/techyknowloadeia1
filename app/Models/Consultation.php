<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    protected $fillable = [
        'user_id',
        'patient_name',
        'patient_age',
        'patient_gender',
        'address',
        'disease_case',
        'symptoms',
        'diagnosis',
        'treatment_notes',
        'consultation_date',
        'attending_nurse',
        'status',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'patient_age' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

