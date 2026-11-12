<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineReturn extends Model
{
    protected $fillable = [
        'user_id',
        'medicine_request_id',
        'medicine_id',
        'medicine_name',
        'previous_quantity',
        'new_quantity',
        'quantity_returned',
        'reason',
        'recorded_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicineRequest(): BelongsTo
    {
        return $this->belongsTo(MedicineRequest::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

