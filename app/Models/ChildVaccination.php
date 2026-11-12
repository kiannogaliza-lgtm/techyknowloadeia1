<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ChildVaccination extends Model
{
    protected $fillable = [
        'user_id',
        'child_name',
        'child_dob',
        'child_gender',
        'vaccine_name',
        'dose_number',
        'date_administered',
        'administered_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'child_dob' => 'date',
        'date_administered' => 'date',
    ];

    /**
     * Parent or guardian resident user.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Compute child age label (e.g., "3 months old (Infant)", "1 year old (Toddler)").
     */
    public function getAgeCategoryAttribute(): string
    {
        if (!$this->child_dob) return 'Age unknown';

        $dob = Carbon::parse($this->child_dob)->startOfDay();
        $today = Carbon::today();

        $diffDays = (int) $dob->diffInDays($today);
        $diffMonths = (int) $dob->diffInMonths($today);
        $diffYears = (int) $dob->diffInYears($today);

        if ($diffDays <= 28) {
            $dayLabel = $diffDays === 1 ? '1 day' : "{$diffDays} days";
            return "{$dayLabel} old (Newborn / Neonate)";
        } elseif ($diffMonths < 12) {
            $moLabel = $diffMonths === 1 ? '1 mo' : "{$diffMonths} mos";
            return "{$moLabel} old (Infant)";
        } elseif ($diffYears <= 2) {
            $yrLabel = $diffYears === 1 ? '1 yr' : "{$diffYears} yrs";
            return "{$yrLabel} old (Toddler)";
        } else {
            return "{$diffYears} yrs old (Child)";
        }
    }
}

