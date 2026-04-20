<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Notifications\AppointmentConfirmed;
use App\Notifications\AppointmentCancelled;
use App\Notifications\AppointmentReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // ─── List appointments ───────────────────────────────────
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Appointment::with(['patient.user', 'doctor.user']);

        // Filter based on role
        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->doctor->id);
        } elseif ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        }

        // Filters
        if ($request->status)  $query->where('status', $request->status);
        if ($request->date)    $query->whereDate('scheduled_at', $request->date);
        if ($request->doctor_id && !$user->isDoctor()) $query->where('doctor_id', $request->doctor_id);

        $appointments = $query->orderBy('scheduled_at', 'desc')->paginate(15);
        $doctors      = Doctor::with('user')->where(function($q) {
            $q->whereHas('user', fn($u) => $u->where('is_active', true));
        })->get();

        return view('appointments.index', compact('appointments', 'doctors'));
    }

    // ─── Show create form ────────────────────────────────────
    public function create(Request $request)
    {
        $doctors  = Doctor::with('user')->get();
        $patients = Patient::with('user')->get();
        $selectedDoctor = $request->doctor_id ? Doctor::with('user', 'availabilities')->find($request->doctor_id) : null;

        return view('appointments.create', compact('doctors', 'patients', 'selectedDoctor'));
    }

    // ─── Store new appointment ───────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id'    => 'required|exists:doctors,id',
            'patient_id'   => 'required|exists:patients,id',
            'scheduled_at' => 'required|date|after:now',
            'reason'       => 'required|string|max:500',
            'notes'        => 'nullable|string|max:1000',
        ]);

        // Check for conflicts
        $doctor = Doctor::findOrFail($request->doctor_id);
        $conflictExists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('scheduled_at', $request->scheduled_at)
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();

        if ($conflictExists) {
            return back()->withErrors(['scheduled_at' => 'Ce créneau est déjà réservé. Veuillez choisir un autre.'])->withInput();
        }

        $appointment = Appointment::create([
            'patient_id'   => $request->patient_id,
            'doctor_id'    => $request->doctor_id,
            'created_by'   => Auth::id(),
            'scheduled_at' => $request->scheduled_at,
            'duration'     => $doctor->consultation_duration,
            'reason'       => $request->reason,
            'notes'        => $request->notes,
            'status'       => Auth::user()->isPatient() ? 'pending' : 'confirmed',
        ]);

        // Send notification email
        $appointment->patient->user->notify(new AppointmentConfirmed($appointment));

        return redirect()->route('appointments.show', $appointment)
                         ->with('success', 'Rendez-vous créé avec succès. Un email de confirmation a été envoyé.');
    }

    // ─── Show appointment detail ─────────────────────────────
    public function show(Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);
        $appointment->load(['patient.user', 'doctor.user', 'consultation.prescription.items']);
        return view('appointments.show', compact('appointment'));
    }

    // ─── Show edit form ──────────────────────────────────────
    public function edit(Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);
        abort_if(!$appointment->canBeCancelled(), 403, 'Ce rendez-vous ne peut plus être modifié.');

        $doctors  = Doctor::with('user')->get();
        $patients = Patient::with('user')->get();
        return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    // ─── Update appointment ──────────────────────────────────
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'reason'       => 'required|string|max:500',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $appointment->update($request->only('scheduled_at', 'reason', 'notes'));

        return redirect()->route('appointments.show', $appointment)->with('success', 'Rendez-vous mis à jour.');
    }

    // ─── Confirm appointment ─────────────────────────────────
    public function confirm(Appointment $appointment)
    {
        abort_if(!Auth::user()->isAdmin() && !Auth::user()->isSecretary() && !Auth::user()->isDoctor(), 403);
        abort_if(!$appointment->canBeConfirmed(), 422, 'Ce rendez-vous ne peut pas être confirmé.');

        $appointment->update(['status' => 'confirmed']);
        $appointment->patient->user->notify(new AppointmentConfirmed($appointment));

        return back()->with('success', 'Rendez-vous confirmé. Email envoyé au patient.');
    }

    // ─── Cancel appointment ──────────────────────────────────
    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentAccess($appointment);
        abort_if(!$appointment->canBeCancelled(), 422, 'Ce rendez-vous ne peut pas être annulé.');

        $request->validate(['cancellation_reason' => 'nullable|string|max:500']);

        $appointment->update([
            'status'               => 'cancelled',
            'cancellation_reason'  => $request->cancellation_reason,
        ]);

        $appointment->patient->user->notify(new AppointmentCancelled($appointment));

        return redirect()->route('appointments.index')->with('success', 'Rendez-vous annulé.');
    }

    // ─── Get available slots (AJAX) ──────────────────────────
    public function availableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date'      => 'required|date|after_or_equal:today',
        ]);

        $doctor  = Doctor::with('availabilities')->findOrFail($request->doctor_id);
        $date    = Carbon::parse($request->date);
        $dayName = strtolower($date->format('l'));

        $availability = $doctor->availabilities
            ->where('day_of_week', $dayName)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return response()->json(['slots' => []]);
        }

        $slots = [];
        $current = Carbon::parse($date->format('Y-m-d') . ' ' . $availability->start_time);
        $end     = Carbon::parse($date->format('Y-m-d') . ' ' . $availability->end_time);

        while ($current->copy()->addMinutes($doctor->consultation_duration)->lte($end)) {
            $booked = Appointment::where('doctor_id', $doctor->id)
                ->where('scheduled_at', $current)
                ->whereIn('status', ['confirmed', 'pending'])
                ->exists();

            if (!$booked && $current->isFuture()) {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes($doctor->consultation_duration);
        }

        return response()->json(['slots' => $slots]);
    }

    // ─── Calendar view ───────────────────────────────────────
    public function calendar(Request $request)
    {
        $user   = Auth::user();
        $query  = Appointment::with(['patient.user', 'doctor.user'])
                    ->whereIn('status', ['confirmed', 'pending']);

        if ($user->isDoctor())  $query->where('doctor_id', $user->doctor->id);
        if ($user->isPatient()) $query->where('patient_id', $user->patient->id);

        $appointments = $query->get()->map(fn($a) => [
            'id'    => $a->id,
            'title' => $a->patient->user->name . ' - ' . $a->doctor->user->name,
            'start' => $a->scheduled_at->toIso8601String(),
            'end'   => $a->end_time->toIso8601String(),
            'color' => match($a->status) { 'confirmed' => '#198754', 'pending' => '#ffc107', default => '#6c757d' },
            'url'   => route('appointments.show', $a->id),
        ]);

        $doctors = Doctor::with('user')->get();
        return view('appointments.calendar', compact('appointments', 'doctors'));
    }

    // ─── Authorization helper ────────────────────────────────
    private function authorizeAppointmentAccess(Appointment $appointment): void
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->isSecretary()) return;
        if ($user->isDoctor() && $appointment->doctor_id === $user->doctor->id) return;
        if ($user->isPatient() && $appointment->patient_id === $user->patient->id) return;
        abort(403);
    }
}
