<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class AppointmentTest extends TestCase
{
    /** @test */
    public function appointment_status_label_returns_correct_french_label()
    {
        $labels = [
            'pending'   => 'En attente',
            'confirmed' => 'Confirmé',
            'cancelled' => 'Annulé',
            'completed' => 'Terminé',
            'no_show'   => 'Absent',
        ];

        foreach ($labels as $status => $expected) {
            $appointment = new Appointment(['status' => $status]);
            $this->assertEquals($expected, $appointment->getStatusLabelAttribute());
        }
    }

    /** @test */
    public function appointment_status_color_maps_correctly()
    {
        $colors = [
            'pending'   => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'primary',
            'no_show'   => 'secondary',
        ];

        foreach ($colors as $status => $expected) {
            $appointment = new Appointment(['status' => $status]);
            $this->assertEquals($expected, $appointment->getStatusColorAttribute());
        }
    }

    /** @test */
    public function can_be_cancelled_only_for_pending_or_confirmed_future_appointments()
    {
        $future = Carbon::now()->addDays(2);
        $past   = Carbon::now()->subDays(2);

        $pendingFuture   = new Appointment(['status' => 'pending',   'scheduled_at' => $future]);
        $confirmedFuture = new Appointment(['status' => 'confirmed', 'scheduled_at' => $future]);
        $completedFuture = new Appointment(['status' => 'completed', 'scheduled_at' => $future]);
        $pendingPast     = new Appointment(['status' => 'pending',   'scheduled_at' => $past]);

        $this->assertTrue($pendingFuture->canBeCancelled());
        $this->assertTrue($confirmedFuture->canBeCancelled());
        $this->assertFalse($completedFuture->canBeCancelled());
        $this->assertFalse($pendingPast->canBeCancelled());
    }

    /** @test */
    public function can_be_confirmed_only_when_pending()
    {
        $pending   = new Appointment(['status' => 'pending']);
        $confirmed = new Appointment(['status' => 'confirmed']);
        $cancelled = new Appointment(['status' => 'cancelled']);

        $this->assertTrue($pending->canBeConfirmed());
        $this->assertFalse($confirmed->canBeConfirmed());
        $this->assertFalse($cancelled->canBeConfirmed());
    }

    /** @test */
    public function end_time_is_calculated_correctly()
    {
        $start = Carbon::parse('2025-01-15 09:00:00');
        $appointment = new Appointment([
            'scheduled_at' => $start,
            'duration'     => 30,
        ]);

        $expected = Carbon::parse('2025-01-15 09:30:00');
        $this->assertEquals($expected->toTimeString(), $appointment->end_time->toTimeString());
    }
}
