<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    // ─── Create consultation from an appointment ─────────────
    public function create(Appointment $appointment)
    {
        abort_unless(Auth::user()->isDoctor() && $appointment->doctor_id === Auth::user()->doctor->id, 403);
        abort_if($appointment->consultation()->exists(), 422, 'Une consultation existe déjà pour ce rendez-vous.');

        $appointment->load(['patient.user', 'patient']);
        return view('consultations.create', compact('appointment'));
    }

    // ─── Store consultation ──────────────────────────────────
    public function store(Request $request, Appointment $appointment)
    {
        abort_unless(Auth::user()->isDoctor() && $appointment->doctor_id === Auth::user()->doctor->id, 403);

        $request->validate([
            'chief_complaint'          => 'required|string',
            'diagnosis'                => 'required|string',
            'notes'                    => 'nullable|string',
            'weight'                   => 'nullable|numeric|between:1,500',
            'height'                   => 'nullable|numeric|between:30,250',
            'blood_pressure_systolic'  => 'nullable|integer|between:50,300',
            'blood_pressure_diastolic' => 'nullable|integer|between:20,200',
            'temperature'              => 'nullable|numeric|between:34,42',
            'heart_rate'               => 'nullable|integer|between:20,300',
            'price'                    => 'nullable|numeric|min:0',
            'payment_status'           => 'required|in:pending,paid,insurance',
            // Prescription items
            'medications'              => 'nullable|array',
            'medications.*.medication' => 'required_with:medications|string|max:255',
            'medications.*.dosage'     => 'required_with:medications|string|max:255',
            'medications.*.frequency'  => 'required_with:medications|string|max:255',
            'medications.*.duration'   => 'required_with:medications|string|max:255',
            'medications.*.instructions'=> 'nullable|string',
        ]);

        $consultation = Consultation::create([
            'appointment_id'           => $appointment->id,
            'doctor_id'                => $appointment->doctor_id,
            'patient_id'               => $appointment->patient_id,
            'chief_complaint'          => $request->chief_complaint,
            'diagnosis'                => $request->diagnosis,
            'notes'                    => $request->notes,
            'weight'                   => $request->weight,
            'height'                   => $request->height,
            'blood_pressure_systolic'  => $request->blood_pressure_systolic,
            'blood_pressure_diastolic' => $request->blood_pressure_diastolic,
            'temperature'              => $request->temperature,
            'heart_rate'               => $request->heart_rate,
            'price'                    => $request->price ?? $appointment->doctor->consultation_price,
            'payment_status'           => $request->payment_status,
        ]);

        // Create prescription if medications provided
        if ($request->filled('medications')) {
            $prescription = Prescription::create([
                'consultation_id' => $consultation->id,
                'doctor_id'       => $appointment->doctor_id,
                'patient_id'      => $appointment->patient_id,
                'notes'           => $request->prescription_notes,
            ]);

            foreach ($request->medications as $med) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medication'      => $med['medication'],
                    'dosage'          => $med['dosage'],
                    'frequency'       => $med['frequency'],
                    'duration'        => $med['duration'],
                    'instructions'    => $med['instructions'] ?? null,
                ]);
            }
        }

        // Mark appointment as completed
        $appointment->update(['status' => 'completed']);

        return redirect()->route('consultations.show', $consultation)
                         ->with('success', 'Consultation enregistrée avec succès.');
    }

    // ─── Show consultation ───────────────────────────────────
    public function show(Consultation $consultation)
    {
        $this->authorizeAccess($consultation);
        $consultation->load(['appointment', 'doctor.user', 'patient.user', 'prescription.items']);
        return view('consultations.show', compact('consultation'));
    }

    // ─── Edit consultation ───────────────────────────────────
    public function edit(Consultation $consultation)
    {
        abort_unless(Auth::user()->isDoctor() && $consultation->doctor_id === Auth::user()->doctor->id, 403);
        $consultation->load(['prescription.items', 'patient.user']);
        return view('consultations.edit', compact('consultation'));
    }

    // ─── Update consultation ─────────────────────────────────
    public function update(Request $request, Consultation $consultation)
    {
        abort_unless(Auth::user()->isDoctor() && $consultation->doctor_id === Auth::user()->doctor->id, 403);

        $request->validate([
            'diagnosis'      => 'required|string',
            'notes'          => 'nullable|string',
            'payment_status' => 'required|in:pending,paid,insurance',
        ]);

        $consultation->update($request->only('chief_complaint', 'diagnosis', 'notes', 'payment_status', 'price'));

        return redirect()->route('consultations.show', $consultation)->with('success', 'Consultation mise à jour.');
    }

    // ─── Patient history ─────────────────────────────────────
    public function patientHistory(Request $request, $patientId)
    {
        $consultations = Consultation::where('patient_id', $patientId)
            ->with(['doctor.user', 'prescription.items'])
            ->latest()->paginate(10);

        return view('consultations.history', compact('consultations'));
    }

    // ─── Export PDF prescription ─────────────────────────────
    public function exportPrescriptionPdf(Prescription $prescription)
    {
        $prescription->load(['doctor.user', 'patient.user', 'items', 'consultation']);
        $pdf = Pdf::loadView('pdf.prescription', compact('prescription'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download("ordonnance_{$prescription->id}_{$prescription->patient->user->name}.pdf");
    }

    // ─── Export PDF consultation ─────────────────────────────
    public function exportConsultationPdf(Consultation $consultation)
    {
        $this->authorizeAccess($consultation);
        $consultation->load(['doctor.user', 'patient.user', 'prescription.items']);
        $pdf = Pdf::loadView('pdf.consultation', compact('consultation'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download("consultation_{$consultation->id}.pdf");
    }

    private function authorizeAccess(Consultation $consultation): void
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->isSecretary()) return;
        if ($user->isDoctor() && $consultation->doctor_id === $user->doctor->id) return;
        if ($user->isPatient() && $consultation->patient_id === $user->patient->id) return;
        abort(403);
    }
}
