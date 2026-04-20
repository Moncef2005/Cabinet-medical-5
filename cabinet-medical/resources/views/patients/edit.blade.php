@extends('layouts.app')
@section('title', 'Modifier le dossier patient')
@section('page-title', 'Modifier le dossier patient')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">
<form action="{{ route('patients.update', $patient) }}" method="POST">
    @csrf @method('PUT')

    <div class="row g-3">
        {{-- Personal info --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-person me-2 text-primary"></i>Informations personnelles</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $patient->user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Téléphone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $patient->user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Date de naissance</label>
                            <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                                   value="{{ old('birth_date', $patient->birth_date?->format('Y-m-d')) }}">
                            @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Sexe</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Non renseigné</option>
                                <option value="male"   {{ old('gender', $patient->gender) === 'male'   ? 'selected' : '' }}>Homme</option>
                                <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Femme</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Groupe sanguin</label>
                            <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                                <option value="">Non renseigné</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                <option value="{{ $bt }}" {{ old('blood_type', $patient->blood_type) === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                @endforeach
                            </select>
                            @error('blood_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">CIN</label>
                            <input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror"
                                   value="{{ old('cin', $patient->cin) }}" placeholder="AB123456">
                            @error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">N° Assurance</label>
                            <input type="text" name="insurance_number" class="form-control"
                                   value="{{ old('insurance_number', $patient->insurance_number) }}" placeholder="CNSS-XXXX">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Adresse</label>
                            <textarea name="address" class="form-control" rows="2"
                                      placeholder="Adresse complète...">{{ old('address', $patient->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Medical info --}}
            <div class="card">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-heart-pulse me-2 text-danger"></i>Informations médicales</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Allergies connues</label>
                            <textarea name="allergies" class="form-control" rows="2"
                                      placeholder="Ex: Pénicilline, Aspirine, Arachides...">{{ old('allergies', $patient->allergies) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Maladies chroniques</label>
                            <textarea name="chronic_diseases" class="form-control" rows="2"
                                      placeholder="Ex: Diabète type 2, Hypertension...">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Antécédents médicaux</label>
                            <textarea name="medical_history" class="form-control" rows="3"
                                      placeholder="Chirurgies, hospitalisations, traitements passés...">{{ old('medical_history', $patient->medical_history) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Emergency contact --}}
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-telephone-fill me-2 text-warning"></i>Contact d'urgence</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom</label>
                        <input type="text" name="emergency_contact_name" class="form-control"
                               value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                               placeholder="Nom du contact">
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="text" name="emergency_contact_phone" class="form-control"
                               value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                               placeholder="06XXXXXXXX">
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                </button>
                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </div>
    </div>
</form>
</div>
</div>
@endsection
