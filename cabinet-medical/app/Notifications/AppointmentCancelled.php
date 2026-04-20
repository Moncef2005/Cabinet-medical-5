<?php
namespace App\Notifications;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelled extends Notification implements ShouldQueue {
    use Queueable;
    public function __construct(public Appointment $appointment) {}
    public function via($notifiable): array { return ['mail', 'database']; }
    public function toMail($notifiable): MailMessage {
        return (new MailMessage)
            ->subject('❌ Rendez-vous annulé - Cabinet Médical')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre rendez-vous du **{$this->appointment->scheduled_at->format('d/m/Y à H:i')}** a été annulé.")
            ->when($this->appointment->cancellation_reason, fn($m) => $m->line("**Raison :** " . $this->appointment->cancellation_reason))
            ->action('Prendre un nouveau rendez-vous', route('appointments.create'))
            ->salutation('Cordialement, Le Cabinet Médical');
    }
    public function toArray($notifiable): array {
        return ['type' => 'appointment_cancelled', 'appointment_id' => $this->appointment->id,
                'message' => "Rendez-vous du {$this->appointment->scheduled_at->format('d/m/Y')} annulé."];
    }
}
