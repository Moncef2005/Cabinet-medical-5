<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Rendez-vous confirmé - Cabinet Médical')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre rendez-vous a été **confirmé** avec succès.")
            ->line("**Médecin :** Dr. {$this->appointment->doctor->user->name} ({$this->appointment->doctor->specialty})")
            ->line("**Date :** " . $this->appointment->scheduled_at->format('d/m/Y à H:i'))
            ->line("**Motif :** " . $this->appointment->reason)
            ->action('Voir mes rendez-vous', route('appointments.show', $this->appointment->id))
            ->line('Merci de vous présenter 10 minutes avant l\'heure prévue.')
            ->salutation('Cordialement, Le Cabinet Médical');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'           => 'appointment_confirmed',
            'appointment_id' => $this->appointment->id,
            'message'        => "Rendez-vous confirmé pour le {$this->appointment->scheduled_at->format('d/m/Y à H:i')}",
        ];
    }
}
