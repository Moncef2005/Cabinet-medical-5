<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification
{
    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔔 Rappel rendez-vous - Cabinet Médical')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Rappel : vous avez un rendez-vous demain.")
            ->line("**Médecin :** Dr. {$this->appointment->doctor->user->name}")
            ->line("**Date :** " . $this->appointment->scheduled_at->format('d/m/Y à H:i'))
            ->salutation('Cordialement, Le Cabinet Médical');
    }
}
