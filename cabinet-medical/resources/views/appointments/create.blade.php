@extends('layouts.app')
@section('title', 'Nouveau rendez-vous')
@section('page-title', 'Prendre un rendez-vous')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-plus me-2 text-primary"></i>Nouveau rendez-vous</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('appointments.store') }}" method="POST" id="apptForm">
            @csrf

            @if(!auth()->user()->isPatient())
            {{-- Patient selector (for admin/secretary/doctor) --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                    <option value="">Sélectionner un patient...</option>
                    @foreach($patients as $pat)
                    <option value="{{ $pat->id }}" {{ old('patient_id')==$pat->id ? 'selected':'' }}>
                        {{ $pat->user->name }} — {{ $pat->user->phone }}
                    </option>
                    @endforeach
                </select>
                @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @else
            <input type="hidden" name="patient_id" value="{{ auth()->user()->patient->id }}">
            @endif

            {{-- Doctor selector --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Médecin <span class="text-danger">*</span></label>
                <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" id="doctorSelect" required>
                    <option value="">Sélectionner un médecin...</option>
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ old('doctor_id', $selectedDoctor?->id)==$doc->id ? 'selected':'' }}>
                        Dr. {{ $doc->user->name }} — {{ $doc->specialty }} ({{ $doc->consultation_price }} DH)
                    </option>
                    @endforeach
                </select>
                @error('doctor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Date picker --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                <input type="date" id="dateInput" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}" value="{{ old('date') }}">
            </div>

            {{-- Time slots --}}
            <div class="mb-4" id="slotsSection" style="display:none">
                <label class="form-label fw-semibold">Heure disponible <span class="text-danger">*</span></label>
                <input type="hidden" name="scheduled_at" id="scheduledAt" value="{{ old('scheduled_at') }}">
                <div id="slotsContainer" class="d-flex flex-wrap gap-2">
                    <span class="text-muted">Chargement...</span>
                </div>
                @error('scheduled_at')<div class="text-danger mt-1" style="font-size:.85rem">{{ $message }}</div>@enderror
            </div>

            {{-- Reason --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Motif de consultation <span class="text-danger">*</span></label>
                <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror"
                       value="{{ old('reason') }}" placeholder="Ex: Douleur thoracique, Fièvre, Suivi traitement..." required>
                @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Notes additionnelles</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                          placeholder="Informations supplémentaires...">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-calendar-check me-1"></i>Confirmer le rendez-vous</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
const doctorSelect = document.getElementById('doctorSelect');
const dateInput    = document.getElementById('dateInput');
const slotsSection = document.getElementById('slotsSection');
const slotsContainer = document.getElementById('slotsContainer');
const scheduledAt  = document.getElementById('scheduledAt');

function loadSlots() {
    const doctorId = doctorSelect.value;
    const date     = dateInput.value;
    if (!doctorId || !date) { slotsSection.style.display = 'none'; return; }

    slotsSection.style.display = 'block';
    slotsContainer.innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2"></div> Chargement des créneaux...';

    fetch(`{{ route('appointments.slots') }}?doctor_id=${doctorId}&date=${date}`)
        .then(r => r.json())
        .then(data => {
            if (!data.slots || data.slots.length === 0) {
                slotsContainer.innerHTML = '<div class="alert alert-warning mb-0 py-2">Aucun créneau disponible pour cette date. Veuillez choisir une autre date.</div>';
                return;
            }
            slotsContainer.innerHTML = '';
            data.slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-primary';
                btn.textContent = slot;
                btn.dataset.slot = `${date} ${slot}:00`;
                btn.addEventListener('click', () => {
                    document.querySelectorAll('#slotsContainer button').forEach(b => b.classList.remove('btn-primary', 'active'));
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary', 'active');
                    scheduledAt.value = btn.dataset.slot;
                });
                slotsContainer.appendChild(btn);
            });
        })
        .catch(() => {
            slotsContainer.innerHTML = '<div class="alert alert-danger mb-0 py-2">Erreur lors du chargement des créneaux.</div>';
        });
}

doctorSelect.addEventListener('change', loadSlots);
dateInput.addEventListener('change', loadSlots);

// Auto-load if values already set
if (doctorSelect.value && dateInput.value) loadSlots();
</script>
@endpush
