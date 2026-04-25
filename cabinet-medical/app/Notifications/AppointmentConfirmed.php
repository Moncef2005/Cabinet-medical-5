<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmed extends Notification
{
    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Rendez-vous confirmé - Cabinet Médical')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre rendez-vous a été confirmé avec succès.")
            ->line("**Médecin :** Dr. {$this->appointment->doctor->user->name}")
            ->line("**Date :** " . $this->appointment->scheduled_at->format('d/m/Y à H:i'))
            ->line("**Motif :** " . $this->appointment->reason)
            ->line('Merci de vous présenter 10 minutes avant.')
            ->salutation('Cordialement, Le Cabinet Médical');
    }
}
