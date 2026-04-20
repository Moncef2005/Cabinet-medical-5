@extends('layouts.app')
@section('title', 'Calendrier')
@section('page-title', 'Calendrier des rendez-vous')

@push('styles')
<style>
    .fc { font-size: .85rem; }
    .fc-event { cursor: pointer; border-radius: 6px !important; padding: 2px 6px; }
    .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 600 !important; }
    .fc-button { border-radius: 8px !important; font-size: .8rem !important; }
    .fc-button-primary { background: #1a6b9a !important; border-color: #1a6b9a !important; }
    .fc-day-today { background: #f0f7ff !important; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-week me-2 text-primary"></i>Calendrier</h6>
        @if(!auth()->user()->isDoctor())
        <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nouveau RDV
        </a>
        @endif
    </div>
    <div class="card-body p-4">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const events = @json($appointments);

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: events,
        eventClick: function(info) {
            window.location.href = info.event.extendedProps.url || info.event.url;
        },
        eventDidMount: function(info) {
            info.el.setAttribute('title', info.event.title);
        },
        height: 700,
        nowIndicator: true,
        businessHours: {
            daysOfWeek: [1,2,3,4,5,6],
            startTime: '09:00',
            endTime: '18:00',
        },
        slotMinTime: '08:00',
        slotMaxTime: '20:00',
        slotDuration: '00:30:00',
        allDaySlot: false,
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
    });

    calendar.render();
});
</script>
@endpush
