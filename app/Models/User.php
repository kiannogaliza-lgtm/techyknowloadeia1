<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'first_name', 'last_name', 'phone_number', 'email', 'password', 'role', 
    'status', 'is_maintenance', 'last_login_at', 'notifications_read_at', 'profile_photo', 'id_photo',
    'dob', 'age', 'age_category', 'gender', 'blood_type', 'address', 'allergies', 
    'chronic_conditions', 'last_checkup', 'emergency_contact_name', 
    'emergency_contact_relation', 'emergency_contact_phone'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Compute the standard age category according to MedLink classification:
     * - 0-28 days: Newborn / Neonate
     * - 1 month-1 year: Infant
     * - 1-2 years: Toddler
     * - 3-12 years: Child
     * - 13-17 years: Teenager / Adolescent
     * - 18-29 years: Young adult
     * - 30-59 years: Adult
     * - 60 years and above: Senior citizen
     */
    public static function resolveAgeCategory(?int $age): ?string
    {
        if ($age === null) {
            return null;
        }

        if ($age >= 60) {
            return 'Senior citizen';
        } elseif ($age >= 30) {
            return 'Adult';
        } elseif ($age >= 18) {
            return 'Young adult';
        } elseif ($age >= 13) {
            return 'Teenager / Adolescent';
        } elseif ($age >= 3) {
            return 'Child';
        } elseif ($age >= 1) {
            return 'Toddler';
        } else {
            return 'Infant';
        }
    }

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            // Automatically synchronize age_category with age
            if ($user->age !== null) {
                $user->age_category = self::resolveAgeCategory((int) $user->age);
            }
        });
    }

    /**
     * Get the medicine requests made by this user.
     */
    public function medicineRequests(): HasMany
    {
        return $this->hasMany(MedicineRequest::class);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'notifications_read_at' => 'datetime',
            'password' => 'hashed',
            'age' => 'integer',
            'is_maintenance' => 'boolean',
        ];
    }
}
