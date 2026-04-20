@extends('layouts.guest')
@section('title', 'Inscription')
@section('subtitle', 'Créez votre compte patient')
@section('content')
<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Nom complet</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Mohammed Berrada" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="votre@email.com" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Téléphone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0612345678" required>
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Mot de passe</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 8 caractères" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold" style="font-size:.85rem">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-person-plus me-1"></i> Créer mon compte
    </button>
    <p class="text-center text-muted" style="font-size:.85rem">
        Déjà inscrit ? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">Se connecter</a>
    </p>
</form>
@endsection
