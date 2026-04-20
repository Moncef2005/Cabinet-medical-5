@extends('layouts.app')
@section('title', 'Créer un utilisateur')
@section('page-title', 'Créer un utilisateur')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2 text-primary"></i>Nouvel utilisateur</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
            @csrf

            {{-- Role selector --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Rôle <span class="text-danger">*</span></label>
                <div class="row g-2">
                    @foreach(['admin'=>['Administrateur','bi-shield-check','danger'],'doctor'=>['Médecin','bi-heart-pulse','success'],'secretary'=>['Secrétaire','bi-person-badge','warning'],'patient'=>['Patient','bi-person-heart','info']] as $role=>[$label,$icon,$color])
                    <div class="col-6 col-md-3">
                        <input type="radio" class="btn-check" name="role" id="role_{{ $role }}" value="{{ $role }}" {{ old('role','patient')===$role ? 'checked':'' }}>
                        <label class="btn btn-outline-{{ $color }} w-100 py-3" for="role_{{ $role }}">
                            <i class="bi {{ $icon }} d-block mb-1 fs-4"></i>
                            <span style="font-size:.8rem">{{ $label }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('role')<div class="text-danger mt-1" style="font-size:.85rem">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Prénom NOM">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@exemple.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Téléphone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0612345678">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 8 caractères">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                </div>
            </div>

            {{-- Doctor-specific fields --}}
            <div id="doctor-fields" class="mt-4 p-3 bg-light rounded" style="display:none">
                <h6 class="fw-semibold text-success mb-3"><i class="bi bi-heart-pulse me-1"></i>Informations du médecin</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Spécialité <span class="text-danger">*</span></label>
                        <select name="specialty" class="form-select @error('specialty') is-invalid @enderror">
                            <option value="">Sélectionner...</option>
                            @foreach(['Médecine Générale','Cardiologie','Dermatologie','Gynécologie-Obstétrique','Neurologie','Ophtalmologie','ORL','Pédiatrie','Psychiatrie','Radiologie','Rhumatologie','Urologie','Chirurgie Générale','Endocrinologie','Gastro-entérologie','Pneumologie'] as $spec)
                            <option value="{{ $spec }}" {{ old('specialty')===$spec ? 'selected':'' }}>{{ $spec }}</option>
                            @endforeach
                        </select>
                        @error('specialty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">N° d'ordre <span class="text-danger">*</span></label>
                        <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" value="{{ old('license_number') }}" placeholder="MG-001">
                        @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Prix consultation (DH)</label>
                        <input type="number" name="consultation_price" class="form-control" value="{{ old('consultation_price', 200) }}" min="0" step="10">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Durée consultation (min)</label>
                        <input type="number" name="consultation_duration" class="form-control" value="{{ old('consultation_duration', 30) }}" min="10" max="120" step="5">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Présentation du médecin...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Créer l'utilisateur</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.getElementById('doctor-fields').style.display =
            radio.value === 'doctor' ? 'block' : 'none';
    });
});
// Init
const selected = document.querySelector('input[name="role"]:checked');
if (selected && selected.value === 'doctor') {
    document.getElementById('doctor-fields').style.display = 'block';
}
</script>
@endpush
