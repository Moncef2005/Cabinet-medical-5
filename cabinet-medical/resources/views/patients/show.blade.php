@extends('layouts.app')
@section('title', 'Dossier patient')
@section('page-title', 'Dossier médical')

@section('content')
<div class="row g-3">
    {{-- Patient info card --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body p-4 text-center">
                <div class="avatar mx-auto mb-3" style="width:72px;height:72px;font-size:1.8rem;background:{{ $patient->gender === 'female' ? '#e91e8c' : '#1a6b9a' }}">
                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-0">{{ $patient->user->name }}</h5>
                <div class="text-muted mb-3">{{ $patient->user->email }}</div>
                @if($patient->blood_type)
                <span class="badge bg-danger fs-6 mb-3">{{ $patient->blood_type }}</span>
                @endif
                <div class="row g-2 text-start">
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="text-muted" style="font-size:.72rem">Âge</div>
                            <div class="fw-bold">{{ $patient->age ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="text-muted" style="font-size:.72rem">Sexe</div>
                            <div class="fw-bold">{{ $patient->gender === 'male' ? '♂ Homme' : '♀ Femme' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">Informations</h6>
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
            </div>
            <div class="card-body p-4">
                <div class="row g-2" style="font-size:.85rem">
                    @if($patient->birth_date)
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Naissance</span>
                        <span class="fw-semibold">{{ $patient->birth_date->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    @if($patient->cin)
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">CIN</span>
                        <span class="fw-semibold">{{ $patient->cin }}</span>
                    </div>
                    @endif
                    @if($patient->user->phone)
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Téléphone</span>
                        <span class="fw-semibold">{{ $patient->user->phone }}</span>
                    </div>
                    @endif
                    @if($patient->insurance_number)
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Assurance</span>
                        <span class="fw-semibold">{{ $patient->insurance_number }}</span>
                    </div>
                    @endif
                    @if($patient->address)
                    <div class="col-12">
                        <span class="text-muted d-block mb-1">Adresse</span>
                        <span class="fw-semibold">{{ $patient->address }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Medical alerts --}}
        @if($patient->allergies || $patient->chronic_diseases)
        <div class="card border-danger">
            <div class="card-header py-3 px-4 bg-danger bg-opacity-10">
                <h6 class="mb-0 fw-semibold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Alertes médicales</h6>
            </div>
            <div class="card-body p-4">
                @if($patient->allergies)
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Allergies</div>
                    <div class="fw-semibold text-danger" style="font-size:.875rem">{{ $patient->allergies }}</div>
                </div>
                @endif
                @if($patient->chronic_diseases)
                <div>
                    <div class="text-muted mb-1" style="font-size:.8rem">Maladies chroniques</div>
                    <div class="fw-semibold" style="font-size:.875rem">{{ $patient->chronic_diseases }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        {{-- Quick actions --}}
        @if(!auth()->user()->isPatient())
        <div class="card mb-3">
            <div class="card-body p-3 d-flex gap-2 flex-wrap">
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-calendar-plus me-1"></i>Nouveau rendez-vous
                </a>
                @if($patient->upcoming_appointment)
                <a href="{{ route('appointments.show', $patient->upcoming_appointment) }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-calendar-check me-1"></i>Prochain RDV: {{ $patient->upcoming_appointment->scheduled_at->format('d/m/Y H:i') }}
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Recent consultations --}}
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Historique des consultations</h6>
            </div>
            <div class="card-body p-0">
                @forelse($recentConsultations as $consult)
                <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom">
                    <div class="text-center" style="min-width:50px">
                        <div class="fw-semibold text-primary" style="font-size:.8rem">{{ $consult->created_at->format('d/m') }}</div>
                        <div class="text-muted" style="font-size:.72rem">{{ $consult->created_at->format('Y') }}</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem">Dr. {{ $consult->doctor->user->name }}</div>
                        <div class="text-muted" style="font-size:.8rem">{{ $consult->doctor->specialty }}</div>
                        <div style="font-size:.85rem;margin-top:4px">{{ \Illuminate\Support\Str::limit($consult->diagnosis, 80) }}</div>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('consultations.show', $consult) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                        @if($consult->prescription)
                        <a href="{{ route('prescriptions.pdf', $consult->prescription) }}" class="btn btn-sm btn-outline-danger">PDF</a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-clipboard-x" style="font-size:2rem"></i>
                    <p class="mt-2 mb-0">Aucune consultation enregistrée</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Upcoming appointments --}}
        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Rendez-vous à venir</h6>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingAppointments as $appt)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div>
                        <div class="fw-semibold" style="font-size:.875rem">{{ $appt->scheduled_at->format('d/m/Y H:i') }}</div>
                        <div class="text-muted" style="font-size:.8rem">Dr. {{ $appt->doctor->user->name }} · {{ $appt->reason }}</div>
                    </div>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <span class="badge bg-{{ $appt->status_color }}-subtle text-{{ $appt->status_color }} rounded-pill">{{ $appt->status_label }}</span>
                        <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-secondary">Voir</a>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.875rem">Aucun rendez-vous à venir</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
