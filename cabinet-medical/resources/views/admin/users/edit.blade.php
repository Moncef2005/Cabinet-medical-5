@extends('layouts.app')
@section('title', 'Modifier l\'utilisateur')
@section('page-title', 'Modifier l\'utilisateur')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2 text-primary"></i>Modifier — {{ $user->name }}</h6>
        <span class="badge bg-{{ ['admin'=>'danger','doctor'=>'success','secretary'=>'warning','patient'=>'info'][$user->role] ?? 'secondary' }}-subtle text-{{ ['admin'=>'danger','doctor'=>'success','secretary'=>'warning','patient'=>'info'][$user->role] ?? 'secondary' }} border rounded-pill px-3">
            {{ $user->role_label }}
        </span>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Téléphone</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $user->phone) }}" placeholder="0612345678">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Rôle <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="admin"     {{ old('role',$user->role)==='admin'     ?'selected':'' }}>Administrateur</option>
                        <option value="doctor"    {{ old('role',$user->role)==='doctor'    ?'selected':'' }}>Médecin</option>
                        <option value="secretary" {{ old('role',$user->role)==='secretary' ?'selected':'' }}>Secrétaire</option>
                        <option value="patient"   {{ old('role',$user->role)==='patient'   ?'selected':'' }}>Patient</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nouveau mot de passe <span class="text-muted fw-normal">(laisser vide pour ne pas changer)</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimum 8 caractères">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirmer mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Compte actif</label>
                    </div>
                </div>
            </div>

            @if($user->isDoctor() && $user->doctor)
            <div class="mt-4 p-3 bg-light rounded">
                <h6 class="fw-semibold text-success mb-3"><i class="bi bi-heart-pulse me-1"></i>Informations médecin</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Spécialité</label>
                        <select name="specialty" class="form-select">
                            @foreach(['Médecine Générale','Cardiologie','Dermatologie','Gynécologie-Obstétrique','Neurologie','Ophtalmologie','ORL','Pédiatrie','Psychiatrie','Radiologie','Rhumatologie','Urologie','Chirurgie Générale','Endocrinologie','Gastro-entérologie','Pneumologie'] as $spec)
                            <option value="{{ $spec }}" {{ old('specialty',$user->doctor->specialty)===$spec ?'selected':'' }}>{{ $spec }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Prix consultation (DH)</label>
                        <input type="number" name="consultation_price" class="form-control" value="{{ old('consultation_price',$user->doctor->consultation_price) }}" min="0" step="10">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Durée (min)</label>
                        <input type="number" name="consultation_duration" class="form-control" value="{{ old('consultation_duration',$user->doctor->consultation_duration) }}" min="10" max="120" step="5">
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
