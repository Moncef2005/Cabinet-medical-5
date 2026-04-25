<?php
namespace App\Notifications;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification  {
    use Queueable;
    public function __construct(public Appointment $appointment) {}
    public function via($notifiable): array { return ['mail', 'database']; }
    public function toMail($notifiable): MailMessage {
        return (new MailMessage)
            ->subject('🔔 Rappel rendez-vous - Cabinet Médical')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Rappel : vous avez un rendez-vous **demain**.")
            ->line("**Médecin :** Dr. {$this->appointment->doctor->user->name}")
            ->line("**Date :** " . $this->appointment->scheduled_at->format('d/m/Y à H:i'))
            ->action('Voir le rendez-vous', route('appointments.show', $this->appointment->id))
            ->salutation('Cordialement, Le Cabinet Médical');
    }
    public function toArray($notifiable): array {
        return ['type' => 'appointment_reminder', 'appointment_id' => $this->appointment->id,
                'message' => "Rappel : rendez-vous le {$this->appointment->scheduled_at->format('d/m/Y à H:i')}"];
    }
}
