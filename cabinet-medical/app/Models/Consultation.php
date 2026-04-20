<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'chief_complaint',
        'diagnosis',
        'notes',
        'weight',
        'height',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'temperature',
        'heart_rate',
        'price',
        'payment_status',
    ];

    protected $casts = [
        'weight'      => 'decimal:2',
        'height'      => 'decimal:2',
        'temperature' => 'decimal:1',
        'price'       => 'decimal:2',
    ];

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Prescription::class);
    }

    public function getBloodPressureAttribute(): string
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return $this->blood_pressure_systolic . '/' . $this->blood_pressure_diastolic . ' mmHg';
        }
        return 'Non mesuré';
    }

    public function getBmiAttribute(): ?float
    {
        if ($this->weight && $this->height) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 1);
        }
        return null;
    }
}
