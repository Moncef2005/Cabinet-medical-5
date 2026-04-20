@extends('layouts.app')
@section('title', 'Détail rendez-vous')
@section('page-title', 'Détail du rendez-vous')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Rendez-vous #{{ $appointment->id }}</h6>
                <span class="badge bg-{{ $appointment->status_color }}-subtle text-{{ $appointment->status_color }} border border-{{ $appointment->status_color }}-subtle rounded-pill px-3 py-2">
                    {{ $appointment->status_label }}
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Patient</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar" style="width:48px;height:48px;font-size:1.1rem">
                                {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $appointment->patient->user->name }}</div>
                                <div class="text-muted" style="font-size:.85rem">{{ $appointment->patient->user->email }}</div>
                                <div class="text-muted" style="font-size:.85rem">{{ $appointment->patient->user->phone }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Médecin</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar" style="width:48px;height:48px;font-size:1.1rem;background:#198754">
                                {{ strtoupper(substr($appointment->doctor->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">Dr. {{ $appointment->doctor->user->name }}</div>
                                <div class="text-muted" style="font-size:.85rem">{{ $appointment->doctor->specialty }}</div>
                                <div class="text-muted" style="font-size:.85rem">{{ $appointment->doctor->consultation_price }} DH</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Date & Heure</h6>
                        <div class="fw-semibold">{{ $appointment->scheduled_at->format('d/m/Y') }}</div>
                        <div class="text-muted">{{ $appointment->scheduled_at->format('H:i') }} – {{ $appointment->end_time->format('H:i') }} ({{ $appointment->duration }} min)</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Motif</h6>
                        <div class="fw-semibold">{{ $appointment->reason }}</div>
                    </div>
                    @if($appointment->notes)
                    <div class="col-12">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Notes</h6>
                        <div class="bg-light rounded p-3" style="font-size:.875rem">{{ $appointment->notes }}</div>
                    </div>
                    @endif
                    @if($appointment->cancellation_reason)
                    <div class="col-12">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Motif d'annulation</h6>
                        <div class="bg-danger bg-opacity-10 rounded p-3 text-danger" style="font-size:.875rem">{{ $appointment->cancellation_reason }}</div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer px-4 py-3 d-flex gap-2 flex-wrap">
                @if($appointment->canBeConfirmed() && (auth()->user()->isAdmin() || auth()->user()->isSecretary()))
                <form action="{{ route('appointments.confirm', $appointment) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Confirmer</button>
                </form>
                @endif
                @if(auth()->user()->isDoctor() && $appointment->status === 'confirmed' && !$appointment->consultation)
                <a href="{{ route('consultations.create', $appointment) }}" class="btn btn-primary">
                    <i class="bi bi-clipboard-plus me-1"></i>Démarrer la consultation
                </a>
                @endif
                @if($appointment->consultation)
                <a href="{{ route('consultations.show', $appointment->consultation) }}" class="btn btn-outline-primary">
                    <i class="bi bi-clipboard2-pulse me-1"></i>Voir la consultation
                </a>
                @endif
                @if($appointment->canBeCancelled())
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="bi bi-x-lg me-1"></i>Annuler
                </button>
                @endif
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary ms-auto">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
            </div>
        </div>
    </div>

    {{-- Consultation summary if exists --}}
    @if($appointment->consultation)
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Consultation</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="text-muted mb-1" style="font-size:.8rem">Diagnostic</div>
                    <div class="fw-semibold" style="font-size:.875rem">{{ $appointment->consultation->diagnosis }}</div>
                </div>
                @if($appointment->consultation->prescription)
                <div class="mb-3">
                    <div class="text-muted mb-1" style="font-size:.8rem">Ordonnance</div>
                    <div class="fw-semibold text-success" style="font-size:.875rem">
                        <i class="bi bi-check-circle me-1"></i>{{ $appointment->consultation->prescription->items->count() }} médicament(s)
                    </div>
                </div>
                <a href="{{ route('prescriptions.pdf', $appointment->consultation->prescription) }}" class="btn btn-danger btn-sm w-100">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Télécharger l'ordonnance (PDF)
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Cancel modal --}}
@if($appointment->canBeCancelled())
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annuler le rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p>Confirmer l'annulation du rendez-vous du <strong>{{ $appointment->scheduled_at->format('d/m/Y à H:i') }}</strong> ?</p>
                    <div class="mb-3">
                        <label class="form-label">Motif d'annulation</label>
                        <textarea name="cancellation_reason" class="form-control" rows="2" placeholder="Raison de l'annulation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
