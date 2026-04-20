<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;

        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->today()
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('patient.user')
            ->orderBy('scheduled_at')
            ->get();

        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->upcoming()
            ->with('patient.user')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $stats = [
            'today_appointments'   => $todayAppointments->count(),
            'month_consultations'  => Consultation::where('doctor_id', $doctor->id)->whereMonth('created_at', now()->month)->count(),
            'total_patients'       => Appointment::where('doctor_id', $doctor->id)->distinct('patient_id')->count('patient_id'),
            'monthly_revenue'      => Consultation::where('doctor_id', $doctor->id)->whereMonth('created_at', now()->month)->sum('price'),
        ];

        return view('doctor.dashboard', compact('doctor', 'todayAppointments', 'upcomingAppointments', 'stats'));
    }
}
