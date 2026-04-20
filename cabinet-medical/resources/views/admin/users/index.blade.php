@extends('layouts.app')
@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-people me-2 text-primary"></i>Utilisateurs</h6>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Nouvel utilisateur
        </a>
    </div>

    {{-- Filters --}}
    <div class="card-body border-bottom py-3 px-4">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher par nom, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Tous les rôles</option>
                    <option value="admin"     {{ request('role')=='admin'     ? 'selected':'' }}>Administrateur</option>
                    <option value="doctor"    {{ request('role')=='doctor'    ? 'selected':'' }}>Médecin</option>
                    <option value="secretary" {{ request('role')=='secretary' ? 'selected':'' }}>Secrétaire</option>
                    <option value="patient"   {{ request('role')=='patient'   ? 'selected':'' }}>Patient</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm w-100">Réinitialiser</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">Utilisateur</th>
                    <th>Rôle</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="background:{{ $user->isDoctor() ? '#198754' : ($user->isAdmin() ? '#dc3545' : '#1a6b9a') }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleColors = ['admin'=>'danger','doctor'=>'success','secretary'=>'warning','patient'=>'info'];
                            $color = $roleColors[$user->role] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle rounded-pill px-3">
                            {{ $user->role_label }}
                        </span>
                    </td>
                    <td style="font-size:.875rem">{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Actif</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Désactivé</span>
                        @endif
                    </td>
                    <td style="font-size:.875rem">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->isDoctor())
                            <a href="{{ route('admin.doctors.availability', $user->doctor) }}" class="btn btn-sm btn-outline-success" title="Disponibilités">
                                <i class="bi bi-calendar-week"></i>
                            </a>
                            @endif
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}-circle"></i>
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="bi bi-people" style="font-size:2rem"></i><br>Aucun utilisateur trouvé
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="card-footer py-3 px-4">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
