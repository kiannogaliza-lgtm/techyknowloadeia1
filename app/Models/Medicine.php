<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'category',
        'stock',
        'unit',
        'dosage',
        'dosage_times_per_day',
        'dosage_days',
        'min_age',
        'max_age',
        'reorder_level',
        'request_limit',
        'release_date',
        'expiration_date',
    ];

    protected $casts = [
        'stock' => 'integer',
        'reorder_level' => 'integer',
        'request_limit' => 'integer',
        'dosage_times_per_day' => 'integer',
        'dosage_days' => 'integer',
        'min_age' => 'integer',
        'max_age' => 'integer',
    ];

    /**
     * Human-readable age limit range, e.g. "12 - 65 yrs", "18+ yrs", "Up to 12 yrs", or "All ages".
     */
    public function getAgeLimitLabelAttribute(): string
    {
        if ($this->min_age !== null && $this->max_age !== null) {
            return "{$this->min_age} - {$this->max_age} yrs";
        } elseif ($this->min_age !== null) {
            return "{$this->min_age}+ yrs";
        } elseif ($this->max_age !== null) {
            return "Up to {$this->max_age} yrs";
        }
        return 'All ages';
    }

    /**
     * Check if a given patient age is suitable for this medicine.
     */
    public function isAgeAllowed(?int $age): bool
    {
        if ($age === null) {
            return true;
        }
        if ($this->min_age !== null && $age < $this->min_age) {
            return false;
        }
        if ($this->max_age !== null && $age > $this->max_age) {
            return false;
        }
        return true;
    }

    /**
     * Get the computed stock status.
     * - "In Stock" when stock > reorder_level
     * - "Low Stock" when stock > 0 but <= reorder_level
     * - "Critical" when stock is 0
     */
    public function getStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Critical';
        }

        if ($this->stock <= $this->reorder_level) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    protected $appends = ['status', 'age_limit_label'];
}
