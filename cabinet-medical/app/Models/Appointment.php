<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'created_by',
        'scheduled_at',
        'duration',
        'status',
        'reason',
        'notes',
        'cancellation_reason',
        'reminder_sent',
    ];

    protected $casts = [
        'scheduled_at'  => 'datetime',
        'reminder_sent' => 'boolean',
    ];

    // ===================== RELATIONSHIPS =====================

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function consultation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Consultation::class);
    }

    // ===================== SCOPES =====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())
                     ->whereIn('status', ['confirmed', 'pending']);
    }

    // ===================== HELPERS =====================

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'En attente',
            'confirmed' => 'Confirmé',
            'cancelled' => 'Annulé',
            'completed' => 'Terminé',
            'no_show'   => 'Absent',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'primary',
            'no_show'   => 'secondary',
            default     => 'secondary',
        };
    }

    public function getEndTimeAttribute(): \Carbon\Carbon
    {
        return $this->scheduled_at->addMinutes($this->duration);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'])
            && $this->scheduled_at->isFuture();
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending';
    }
}
