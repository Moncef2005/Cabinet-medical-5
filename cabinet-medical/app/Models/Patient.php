<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'blood_type',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'allergies',
        'chronic_diseases',
        'medical_history',
        'cin',
        'insurance_number',
    ];

    protected $casts = [
        'birth_date' => 'date',
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

    public function prescriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    // ===================== HELPERS =====================

    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            'male'   => 'Homme',
            'female' => 'Femme',
            default  => 'Non renseigné',
        };
    }

    public function getLastConsultationAttribute(): ?Consultation
    {
        return $this->consultations()->latest()->first();
    }

    public function getUpcomingAppointmentAttribute(): ?Appointment
    {
        return $this->appointments()
            ->where('scheduled_at', '>=', now())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('scheduled_at')
            ->first();
    }
}
