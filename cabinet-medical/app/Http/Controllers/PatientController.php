<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            })->orWhere('cin', 'like', "%{$request->search}%");
        }

        if ($request->blood_type) {
            $query->where('blood_type', $request->blood_type);
        }

        $patients = $query->latest()->paginate(15);
        return view('patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $this->authorizeAccess($patient);
        $patient->load(['user', 'appointments.doctor.user', 'consultations.doctor.user', 'prescriptions.items']);
        $recentConsultations = $patient->consultations()->with('doctor.user')->latest()->take(5)->get();
        $upcomingAppointments = $patient->appointments()->upcoming()->with('doctor.user')->get();
        return view('patients.show', compact('patient', 'recentConsultations', 'upcomingAppointments'));
    }

    public function edit(Patient $patient)
    {
        $this->authorizeAccess($patient);
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $this->authorizeAccess($patient);

        $request->validate([
            'name'                    => 'required|string|max:255',
            'phone'                   => 'nullable|string|max:20',
            'birth_date'              => 'nullable|date|before:today',
            'gender'                  => 'nullable|in:male,female',
            'blood_type'              => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address'                 => 'nullable|string|max:500',
            'cin'                     => "nullable|string|max:20|unique:patients,cin,{$patient->id}",
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'allergies'               => 'nullable|string',
            'chronic_diseases'        => 'nullable|string',
            'medical_history'         => 'nullable|string',
        ]);

        $patient->user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        $patient->update($request->only(
            'birth_date', 'gender', 'blood_type', 'address', 'cin',
            'emergency_contact_name', 'emergency_contact_phone',
            'allergies', 'chronic_diseases', 'medical_history', 'insurance_number'
        ));

        return redirect()->route('patients.show', $patient)->with('success', 'Dossier patient mis à jour.');
    }

    // ─── Patient's own dashboard ─────────────────────────────
    public function dashboard()
    {
        $user    = Auth::user();
        $patient = $user->patient;
        $patient->load(['appointments.doctor.user', 'consultations.doctor.user', 'prescriptions']);

        $upcomingAppointments = $patient->appointments()->upcoming()->with('doctor.user')->orderBy('scheduled_at')->take(3)->get();
        $recentConsultations  = $patient->consultations()->with('doctor.user')->latest()->take(3)->get();

        return view('patient.dashboard', compact('patient', 'upcomingAppointments', 'recentConsultations'));
    }

    private function authorizeAccess(Patient $patient): void
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->isSecretary() || $user->isDoctor()) return;
        if ($user->isPatient() && $patient->user_id === $user->id) return;
        abort(403);
    }
}
