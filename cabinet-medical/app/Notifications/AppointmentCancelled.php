<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelled extends Notification
{
    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('❌ Rendez-vous annulé - Cabinet Médical')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre rendez-vous du **{$this->appointment->scheduled_at->format('d/m/Y à H:i')}** a été annulé.")
            ->action('Prendre un nouveau rendez-vous', route('appointments.create'))
            ->salutation('Cordialement, Le Cabinet Médical');
    }
}
