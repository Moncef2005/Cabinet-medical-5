@extends('layouts.app')
@section('title', 'Patients')
@section('page-title', 'Liste des patients')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-person-heart me-2 text-primary"></i>Patients ({{ $patients->total() }})</h6>
    </div>
    <div class="card-body border-bottom py-3 px-4">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher par nom, email, téléphone, CIN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="blood_type" class="form-select form-select-sm">
                    <option value="">Tous les groupes sanguins</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                    <option value="{{ $bt }}" {{ request('blood_type')===$bt ? 'selected':'' }}>{{ $bt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit"><i class="bi bi-search me-1"></i>Filtrer</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-sm w-100">Réinitialiser</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">Patient</th>
                    <th>Âge / Sexe</th>
                    <th>Groupe sanguin</th>
                    <th>Téléphone</th>
                    <th>Dernière consultation</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="background:{{ $patient->gender === 'female' ? '#e91e8c' : '#1a6b9a' }}">
                                {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $patient->user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $patient->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem">
                        {{ $patient->age ? $patient->age . ' ans' : '—' }}
                        <span class="text-muted">· {{ $patient->gender_label }}</span>
                    </td>
                    <td>
                        @if($patient->blood_type)
                            <span class="badge bg-danger rounded-pill">{{ $patient->blood_type }}</span>
                        @else —
                        @endif
                    </td>
                    <td style="font-size:.875rem">{{ $patient->user->phone ?? '—' }}</td>
                    <td style="font-size:.875rem">
                        @if($patient->last_consultation)
                            {{ $patient->last_consultation->created_at->format('d/m/Y') }}
                            <div class="text-muted" style="font-size:.75rem">Dr. {{ $patient->last_consultation->doctor->user->name }}</div>
                        @else
                            <span class="text-muted">Jamais</span>
                        @endif
                    </td>
                    <td class="px-4 text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-outline-success" title="Nouveau RDV"><i class="bi bi-calendar-plus"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">Aucun patient trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
    <div class="card-footer py-3 px-4">{{ $patients->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
