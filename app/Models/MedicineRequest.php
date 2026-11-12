<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineRequest extends Model
{
    protected $fillable = [
        'user_id',
        'medicine_id',
        'patient_type',
        'patient_name',
        'patient_age',
        'patient_gender',
        'patient_relationship',
        'type',
        'item_service',
        'quantity',
        'delivery_method',
        'notes',
        'status',
        'delivered_by',
        'claimed_by',
        'claimed_relationship',
        'claimed_at',
        'proof_photo',
        'delivery_signature',
        'receipt_number',
    ];

    protected $casts = [
        'patient_age' => 'integer',
        'quantity' => 'integer',
        'claimed_at' => 'datetime',
    ];

    /**
     * Resolves the actual person taking the medicine (either the requester or a family member).
     */
    public function getActualPatientNameAttribute(): string
    {
        if ($this->patient_type === 'other' && !empty($this->patient_name)) {
            $rel = $this->patient_relationship ? " ({$this->patient_relationship})" : '';
            return "{$this->patient_name}{$rel}";
        }
        return $this->user ? "{$this->user->first_name} {$this->user->last_name} (Self)" : 'Self';
    }

    /**
     * Resolves the actual patient's age.
     */
    public function getActualPatientAgeAttribute(): ?int
    {
        if ($this->patient_type === 'other') {
            return $this->patient_age;
        }
        return $this->user ? $this->user->age : null;
    }

    /**
     * Get the user (patient) who made this request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the medicine linked to this request.
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * Generate a formatted request ID like REQ-001.
     */
    public function getRequestIdAttribute(): string
    {
        return 'REQ-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    protected $appends = ['request_id', 'actual_patient_name', 'actual_patient_age'];
}
