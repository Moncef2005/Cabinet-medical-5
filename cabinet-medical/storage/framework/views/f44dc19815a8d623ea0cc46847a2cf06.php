<?php $__env->startSection('title', 'Tableau de bord'); ?>
<?php $__env->startSection('page-title', 'Tableau de bord administrateur'); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a6b9a,#2196f3)">
            <div class="stat-icon"><i class="bi bi-person-heart"></i></div>
            <div>
                <div class="stat-value"><?php echo e(number_format($stats['total_patients'])); ?></div>
                <div class="stat-label">Patients enregistrés</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#198754,#4caf50)">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="stat-value"><?php echo e(number_format($stats['today_appointments'])); ?></div>
                <div class="stat-label">RDV aujourd'hui</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#dc3545,#f44336)">
            <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-value"><?php echo e(number_format($stats['pending_appointments'])); ?></div>
                <div class="stat-label">RDV en attente</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6f42c1,#9c27b0)">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-value"><?php echo e(number_format($stats['monthly_revenue'], 0)); ?> DH</div>
                <div class="stat-label">Revenus ce mois</div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-bar-chart me-2 text-primary"></i>Rendez-vous & Revenus (6 derniers mois)</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="appointmentsRevenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-pie-chart me-2 text-success"></i>RDV par spécialité</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-4">
                <canvas id="specialtyChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-list-check me-2 text-primary"></i>Derniers rendez-vous</h6>
        <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-sm btn-outline-primary">Voir tout</a>
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
                    <th class="px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar"><?php echo e(strtoupper(substr($appt->patient->user->name, 0, 1))); ?></div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem"><?php echo e($appt->patient->user->name); ?></div>
                                <div class="text-muted" style="font-size:.75rem"><?php echo e($appt->patient->user->phone); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:.875rem">Dr. <?php echo e($appt->doctor->user->name); ?></div>
                        <div class="text-muted" style="font-size:.75rem"><?php echo e($appt->doctor->specialty); ?></div>
                    </td>
                    <td>
                        <div style="font-size:.875rem"><?php echo e($appt->scheduled_at->format('d/m/Y')); ?></div>
                        <div class="text-muted" style="font-size:.75rem"><?php echo e($appt->scheduled_at->format('H:i')); ?></div>
                    </td>
                    <td><span style="font-size:.875rem"><?php echo e(\Illuminate\Support\Str::limit($appt->reason, 30)); ?></span></td>
                    <td>
                        <span class="badge bg-<?php echo e($appt->status_color); ?>-subtle text-<?php echo e($appt->status_color); ?> border border-<?php echo e($appt->status_color); ?>-subtle rounded-pill px-3">
                            <?php echo e($appt->status_label); ?>

                        </span>
                    </td>
                    <td class="px-4">
                        <a href="<?php echo e(route('appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">Aucun rendez-vous</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ─── Appointments & Revenue Chart ──────────────────────────────
const apptData   = <?php echo json_encode($appointmentsChart, 15, 512) ?>;
const revenueData = <?php echo json_encode($revenueChart, 15, 512) ?>;

const monthNames = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];

const labels = apptData.map(d => monthNames[d.month - 1] + ' ' + d.year);
const apptValues = apptData.map(d => d.total);
const revenueValues = revenueData.map(d => d.total);

new Chart(document.getElementById('appointmentsRevenueChart'), {
    type: 'bar',
    data: {
        labels: labels.length ? labels : ['Aucune donnée'],
        datasets: [
            {
                label: 'Rendez-vous',
                data: apptValues,
                backgroundColor: 'rgba(26,107,154,0.8)',
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Revenus (DH)',
                data: revenueValues,
                type: 'line',
                borderColor: '#6f42c1',
                backgroundColor: 'rgba(111,66,193,0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                yAxisID: 'y1',
                pointBackgroundColor: '#6f42c1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top' } },
        scales: {
            y:  { type: 'linear', display: true, position: 'left',  title: { display: true, text: 'RDV' } },
            y1: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'DH' }, grid: { drawOnChartArea: false } }
        }
    }
});

// ─── Specialty Pie Chart ───────────────────────────────────────
const specialtyData = <?php echo json_encode($specialtyChart, 15, 512) ?>;
new Chart(document.getElementById('specialtyChart'), {
    type: 'doughnut',
    data: {
        labels: specialtyData.map(d => d.specialty),
        datasets: [{
            data: specialtyData.map(d => d.total),
            backgroundColor: ['#1a6b9a','#198754','#dc3545','#ffc107','#6f42c1','#fd7e14'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 15, font: { size: 11 } } }
        },
        cutout: '65%',
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>