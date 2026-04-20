<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'license_number',
        'description',
        'consultation_price',
        'consultation_duration',
    ];

    protected $casts = [
        'consultation_price' => 'decimal:2',
    ];

    // ===================== RELATIONSHIPS =====================

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function availabilities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoctorAvailability::class);
    }

    public function prescriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    // ===================== HELPERS =====================

    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getFullTitleAttribute(): string
    {
        return 'Dr. ' . $this->user->name . ' - ' . $this->specialty;
    }

    public function isAvailableOn(string $dayOfWeek): bool
    {
        return $this->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->exists();
    }

    public function getTodayAppointmentsCountAttribute(): int
    {
        return $this->appointments()
            ->whereDate('scheduled_at', today())
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();
    }

    public function getMonthlyRevenueAttribute(): float
    {
        return $this->consultations()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('price');
    }
}
