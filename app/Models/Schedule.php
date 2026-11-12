<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_name',
        'type',
        'service',
        'scheduled_date',
        'scheduled_time',
        'notes',
        'status',
        'assigned_to',
        'assigned_bhw_name',
        'assignment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBhw()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'scheduled_time' => 'datetime:H:i',
        ];
    }

    /**
     * Get a formatted schedule ID like SCH-001.
     */
    public function getFormattedIdAttribute(): string
    {
        return 'SCH-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the formatted time (e.g. "9:00 AM").
     */
    public function getFormattedTimeAttribute(): string
    {
        return \Carbon\Carbon::parse($this->scheduled_time)->format('g:i A');
    }
}
