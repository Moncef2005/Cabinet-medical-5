<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send appointment reminders daily at 8am
Schedule::call(function () {
    $tomorrow = now()->addDay()->startOfDay();
    \App\Models\Appointment::whereDate('scheduled_at', $tomorrow)
        ->where('status', 'confirmed')
        ->where('reminder_sent', false)
        ->with('patient.user')
        ->chunk(50, function ($appointments) {
            foreach ($appointments as $appointment) {
                $appointment->patient->user->notify(new \App\Notifications\AppointmentReminder($appointment));
                $appointment->update(['reminder_sent' => true]);
            }
        });
})->dailyAt('08:00')->name('send-appointment-reminders');


