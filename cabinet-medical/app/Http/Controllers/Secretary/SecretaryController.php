<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class SecretaryController extends Controller
{
    public function dashboard()
    {
        $todayAppointments = Appointment::today()
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('scheduled_at')
            ->get();

        $pendingAppointments = Appointment::pending()
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('scheduled_at')
            ->take(10)
            ->get();

        $stats = [
            'today_total'    => $todayAppointments->count(),
            'today_pending'  => $todayAppointments->where('status', 'pending')->count(),
            'total_patients' => Patient::count(),
            'total_doctors'  => Doctor::count(),
        ];

        return view('secretary.dashboard', compact('todayAppointments', 'pendingAppointments', 'stats'));
    }
}
