<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeServiceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'patient_name',
        'service_type',
        'address',
        'contact_number',
        'preferred_date',
        'preferred_time',
        'reason_symptoms',
        'status',
        'assigned_to',
        'assigned_bhw_name',
        'nurse_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    protected $appends = [
        'formatted_id',
        'assigned_name',
    ];

    /**
     * Get the user (resident/patient) who created this request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the assigned BHW / staff user.
     */
    public function assignedBhw(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Formatted request ID like HS-001.
     */
    public function getFormattedIdAttribute(): string
    {
        return 'HS-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get assigned worker display name.
     */
    public function getAssignedNameAttribute(): ?string
    {
        if (!empty($this->assigned_bhw_name)) {
            return $this->assigned_bhw_name;
        }

        if ($this->assignedBhw) {
            return 'BHW ' . $this->assignedBhw->first_name . ' ' . $this->assignedBhw->last_name;
        }

        return null;
    }
}
