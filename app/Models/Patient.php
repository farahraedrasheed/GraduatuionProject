<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'age',
        'gender',
        'phone',
        'address',
        'blood_type',
        'medical_history',
        'patient_type',
        'chronic_disease_type',
        'chronic_disease_type2',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) return null;
        
        $birth = \Carbon\Carbon::parse($this->date_of_birth);
        $now = \Carbon\Carbon::now();
        
        $age = $now->year - $birth->year;
        
        if ($birth->format('m-d') > $now->format('m-d')) {
            $age--;
        }
        
        if ($age < 1) {
            $months = (int) $now->diffInMonths($birth);
            if ($months < 1) {
                $days = (int) $now->diffInDays($birth);
                return $days . ' يوم';
            }
            return $months . ' شهر';
        }
        
        return $age;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalReports(): HasMany
    {
        return $this->hasMany(MedicalReport::class);
    }
}