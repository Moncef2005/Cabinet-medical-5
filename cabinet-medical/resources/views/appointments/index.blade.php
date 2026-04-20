@extends('layouts.app')
@section('title', 'Rendez-vous')
@section('page-title', 'Liste des rendez-vous')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4 flex-wrap gap-2">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Rendez-vous</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('appointments.calendar') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-calendar-week me-1"></i>Calendrier
            </a>
            @if(!auth()->user()->isDoctor())
            <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Nouveau RDV
            </a>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="card-body border-bottom py-3 px-4">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Tous les statuts</option>
                    <option value="pending"   {{ request('status')=='pending'   ?'selected':'' }}>En attente</option>
                    <option value="confirmed" {{ request('status')=='confirmed' ?'selected':'' }}>Confirmé</option>
                    <option value="completed" {{ request('status')=='completed' ?'selected':'' }}>Terminé</option>
                    <option value="cancelled" {{ request('status')=='cancelled' ?'selected':'' }}>Annulé</option>
                    <option value="no_show"   {{ request('status')=='no_show'   ?'selected':'' }}>Absent</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
            </div>
            @if(!auth()->user()->isDoctor())
            <div class="col-md-3">
                <select name="doctor_id" class="form-select form-select-sm">
                    <option value="">Tous les médecins</option>
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ request('doctor_id')==$doc->id ?'selected':'' }}>Dr. {{ $doc->user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit"><i class="bi bi-search me-1"></i>Filtrer</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">Patient</th>
                    <th>Médecin</th>
                    <th>Date & Heure</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar">{{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}</div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $appt->patient->user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $appt->patient->user->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:.875rem">Dr. {{ $appt->doctor->user->name }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $appt->doctor->specialty }}</div>
                    </td>
                    <td>
                        <div style="font-size:.875rem">{{ $appt->scheduled_at->format('d/m/Y') }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $appt->scheduled_at->format('H:i') }}</div>
                    </td>
                    <td><span style="font-size:.875rem">{{ \Illuminate\Support\Str::limit($appt->reason, 35) }}</span></td>
                    <td>
                        <span class="badge bg-{{ $appt->status_color }}-subtle text-{{ $appt->status_color }} border border-{{ $appt->status_color }}-subtle rounded-pill px-3">
                            {{ $appt->status_label }}
                        </span>
                    </td>
                    <td class="px-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-secondary" title="Voir"><i class="bi bi-eye"></i></a>
                            @if($appt->canBeConfirmed() && (auth()->user()->isAdmin() || auth()->user()->isSecretary()))
                            <form action="{{ route('appointments.confirm', $appt) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success" title="Confirmer"><i class="bi bi-check-lg"></i></button>
                            </form>
                            @endif
                            @if(auth()->user()->isDoctor() && $appt->status === 'confirmed' && !$appt->consultation)
                            <a href="{{ route('consultations.create', $appt) }}" class="btn btn-sm btn-primary" title="Créer consultation"><i class="bi bi-clipboard-plus"></i></a>
                            @endif
                            @if($appt->canBeCancelled())
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $appt->id }}" title="Annuler">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Cancel Modal --}}
                @if($appt->canBeCancelled())
                <div class="modal fade" id="cancelModal{{ $appt->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Annuler le rendez-vous</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('appointments.cancel', $appt) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir annuler ce rendez-vous avec <strong>{{ $appt->patient->user->name }}</strong> le {{ $appt->scheduled_at->format('d/m/Y à H:i') }} ?</p>
                                    <div class="mb-3">
                                        <label class="form-label">Motif d'annulation (optionnel)</label>
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
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size:2.5rem;display:block;margin-bottom:.5rem"></i>
                    Aucun rendez-vous trouvé
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($appointments->hasPages())
    <div class="card-footer py-3 px-4">{{ $appointments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
