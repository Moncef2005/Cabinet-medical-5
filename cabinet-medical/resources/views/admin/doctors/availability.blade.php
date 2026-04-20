@extends('layouts.app')
@section('title', 'Disponibilités')
@section('page-title', 'Gestion des disponibilités')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">

<div class="card mb-3 border-0" style="background:linear-gradient(135deg,#198754,#4caf50);color:#fff">
    <div class="card-body p-4 d-flex align-items-center gap-3">
        <div class="avatar" style="width:56px;height:56px;font-size:1.4rem;background:rgba(255,255,255,.2)">
            {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
        </div>
        <div>
            <h5 class="fw-bold mb-0">Dr. {{ $doctor->user->name }}</h5>
            <p class="mb-0 opacity-75">{{ $doctor->specialty }} — Durée consultation : {{ $doctor->consultation_duration }} min</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-week me-2 text-success"></i>Plages horaires de disponibilité</h6>
        <button type="button" class="btn btn-sm btn-outline-success" id="addSlot">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un jour
        </button>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.doctors.availability.update', $doctor) }}" method="POST">
            @csrf

            <div id="availabilityContainer">
                @forelse($availabilities as $i => $av)
                <div class="availability-row row g-2 align-items-center mb-3 p-3 bg-light rounded">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Jour</label>
                        <select name="availabilities[{{ $i }}][day_of_week]" class="form-select form-select-sm" required>
                            @foreach(['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi','saturday'=>'Samedi','sunday'=>'Dimanche'] as $val=>$label)
                            <option value="{{ $val }}" {{ $av->day_of_week===$val ? 'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Heure début</label>
                        <input type="time" name="availabilities[{{ $i }}][start_time]" class="form-control form-control-sm"
                               value="{{ $av->start_time }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Heure fin</label>
                        <input type="time" name="availabilities[{{ $i }}][end_time]" class="form-control form-control-sm"
                               value="{{ $av->end_time }}" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-slot w-100">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
                @empty
                {{-- Default 5-day week if no availabilities --}}
                @foreach(['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi'] as $val=>$label)
                <div class="availability-row row g-2 align-items-center mb-3 p-3 bg-light rounded">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Jour</label>
                        <select name="availabilities[{{ $loop->index }}][day_of_week]" class="form-select form-select-sm" required>
                            @foreach(['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi','saturday'=>'Samedi','sunday'=>'Dimanche'] as $dv=>$dl)
                            <option value="{{ $dv }}" {{ $dv===$val ? 'selected':'' }}>{{ $dl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Heure début</label>
                        <input type="time" name="availabilities[{{ $loop->index }}][start_time]" class="form-control form-control-sm" value="09:00" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Heure fin</label>
                        <input type="time" name="availabilities[{{ $loop->index }}][end_time]" class="form-control form-control-sm" value="17:00" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-slot w-100">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
                @endforeach
                @endforelse
            </div>

            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Enregistrer les disponibilités</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Retour</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
let rowCount = document.querySelectorAll('.availability-row').length;

document.getElementById('addSlot').addEventListener('click', function () {
    const i = rowCount++;
    const html = `
    <div class="availability-row row g-2 align-items-center mb-3 p-3 bg-light rounded">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:.82rem">Jour</label>
            <select name="availabilities[${i}][day_of_week]" class="form-select form-select-sm" required>
                <option value="monday">Lundi</option><option value="tuesday">Mardi</option>
                <option value="wednesday">Mercredi</option><option value="thursday">Jeudi</option>
                <option value="friday">Vendredi</option><option value="saturday">Samedi</option>
                <option value="sunday">Dimanche</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:.82rem">Heure début</label>
            <input type="time" name="availabilities[${i}][start_time]" class="form-control form-control-sm" value="09:00" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:.82rem">Heure fin</label>
            <input type="time" name="availabilities[${i}][end_time]" class="form-control form-control-sm" value="17:00" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-sm btn-outline-danger remove-slot w-100"><i class="bi bi-trash me-1"></i>Supprimer</button>
        </div>
    </div>`;
    document.getElementById('availabilityContainer').insertAdjacentHTML('beforeend', html);
    attachRemove();
});

function attachRemove() {
    document.querySelectorAll('.remove-slot').forEach(btn => {
        btn.onclick = () => btn.closest('.availability-row').remove();
    });
}
attachRemove();
</script>
@endpush
