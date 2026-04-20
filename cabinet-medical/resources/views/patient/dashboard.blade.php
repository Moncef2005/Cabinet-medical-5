@extends('layouts.app')
@section('title', 'Mon espace patient')
@section('page-title', 'Mon espace patient')

@section('content')
<div class="row g-3">
    {{-- Welcome card --}}
    <div class="col-12">
        <div class="card border-0" style="background:linear-gradient(135deg,#1a6b9a,#4da3d4);color:#fff">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1">Bonjour, {{ auth()->user()->name }} 👋</h4>
                    <p class="mb-0 opacity-75">Bienvenue dans votre espace santé</p>
                </div>
                <a href="{{ route('appointments.create') }}" class="btn btn-light fw-semibold">
                    <i class="bi bi-calendar-plus me-1"></i> Prendre un RDV
                </a>
            </div>
        </div>
    </div>

    {{-- Upcoming appointments --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Mes prochains rendez-vous</h6>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingAppointments as $appt)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="avatar bg-primary">
                        <i class="bi bi-heart-pulse text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem">Dr. {{ $appt->doctor->user->name }}</div>
                        <div class="text-muted" style="font-size:.78rem">
                            {{ $appt->doctor->specialty }} · {{ $appt->scheduled_at->format('d/m/Y à H:i') }}
                        </div>
                        <div style="font-size:.78rem">Motif : {{ $appt->reason }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="badge bg-{{ $appt->status_color }}-subtle text-{{ $appt->status_color }} rounded-pill">{{ $appt->status_label }}</span>
                        <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-secondary">Détail</a>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size:2.5rem"></i>
                    <p class="mt-2 mb-0">Aucun rendez-vous à venir</p>
                    <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-primary mt-2">
                        <i class="bi bi-calendar-plus me-1"></i>Prendre un rendez-vous
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-5">
        {{-- Patient info card --}}
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-person-circle me-2 text-primary"></i>Mon dossier</h6>
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
            </div>
            <div class="card-body px-4 py-3">
                <div class="row g-2" style="font-size:.85rem">
                    <div class="col-6">
                        <div class="text-muted mb-1">Date de naissance</div>
                        <div class="fw-semibold">{{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1">Groupe sanguin</div>
                        <div class="fw-semibold">
                            @if($patient->blood_type)
                                <span class="badge bg-danger">{{ $patient->blood_type }}</span>
                            @else — @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted mb-1">Allergies</div>
                        <div class="fw-semibold">{{ $patient->allergies ?? 'Aucune allergie connue' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent consultations --}}
        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Dernières consultations</h6>
            </div>
            <div class="card-body p-0">
                @forelse($recentConsultations as $consult)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="avatar bg-success"><i class="bi bi-clipboard2-check text-white"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem">Dr. {{ $consult->doctor->user->name }}</div>
                        <div class="text-muted" style="font-size:.78rem">{{ $consult->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('consultations.show', $consult) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($consult->prescription)
                        <a href="{{ route('prescriptions.pdf', $consult->prescription) }}" class="btn btn-sm btn-outline-danger" title="Télécharger ordonnance">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.875rem">
                    <i class="bi bi-clipboard-x me-1"></i> Aucune consultation enregistrée
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
