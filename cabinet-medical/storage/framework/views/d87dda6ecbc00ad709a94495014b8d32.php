<?php $__env->startSection('title', 'Tableau de bord médecin'); ?>
<?php $__env->startSection('page-title', 'Mon tableau de bord'); ?>

<?php $__env->startSection('content'); ?>

<div class="card mb-4 border-0" style="background:linear-gradient(135deg,#1a6b9a,#4da3d4);color:#fff">
    <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="fw-bold mb-1">Bonjour, Dr. <?php echo e(auth()->user()->name); ?> 👋</h4>
            <p class="mb-0 opacity-75"><?php echo e($doctor->specialty); ?> · <?php echo e(now()->format('l d F Y')); ?></p>
        </div>
        <div class="text-end">
            <div style="font-size:3rem;opacity:.3"><i class="bi bi-heart-pulse-fill"></i></div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a6b9a,#2196f3)">
            <div class="stat-icon"><i class="bi bi-calendar-day"></i></div>
            <div>
                <div class="stat-value"><?php echo e($stats['today_appointments']); ?></div>
                <div class="stat-label">Consultations aujourd'hui</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#198754,#4caf50)">
            <div class="stat-icon"><i class="bi bi-clipboard2-pulse"></i></div>
            <div>
                <div class="stat-value"><?php echo e($stats['month_consultations']); ?></div>
                <div class="stat-label">Consultations ce mois</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6f42c1,#9c27b0)">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value"><?php echo e($stats['total_patients']); ?></div>
                <div class="stat-label">Patients suivis</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#fd7e14,#ff9800)">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-value"><?php echo e(number_format($stats['monthly_revenue'], 0)); ?> DH</div>
                <div class="stat-label">Revenus ce mois</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-day me-2 text-primary"></i>Programme du jour</h6>
                <a href="<?php echo e(route('appointments.calendar')); ?>" class="btn btn-sm btn-outline-primary">Mon agenda</a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $todayAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom hover-bg">
                    <div class="text-center" style="min-width:50px">
                        <div class="fw-bold text-primary" style="font-size:1rem"><?php echo e($appt->scheduled_at->format('H:i')); ?></div>
                        <div class="text-muted" style="font-size:.7rem"><?php echo e($appt->duration); ?>min</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem"><?php echo e($appt->patient->user->name); ?></div>
                        <div class="text-muted" style="font-size:.78rem"><?php echo e($appt->reason); ?></div>
                    </div>
                    <div class="d-flex gap-1">
                        <span class="badge bg-<?php echo e($appt->status_color); ?>-subtle text-<?php echo e($appt->status_color); ?> rounded-pill"><?php echo e($appt->status_label); ?></span>
                        <?php if($appt->status === 'confirmed' && !$appt->consultation): ?>
                        <a href="<?php echo e(route('consultations.create', $appt)); ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-clipboard-plus"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size:2.5rem"></i>
                    <p class="mt-2 mb-0">Aucun rendez-vous aujourd'hui</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-success"></i>Prochains rendez-vous</h6>
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-sm btn-outline-success">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="avatar bg-success">
                        <?php echo e(strtoupper(substr($appt->patient->user->name, 0, 1))); ?>

                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem"><?php echo e($appt->patient->user->name); ?></div>
                        <div class="text-muted" style="font-size:.78rem">
                            <i class="bi bi-clock me-1"></i><?php echo e($appt->scheduled_at->format('d/m/Y H:i')); ?>

                        </div>
                    </div>
                    <a href="<?php echo e(route('appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-check" style="font-size:2.5rem"></i>
                    <p class="mt-2 mb-0">Aucun prochain rendez-vous</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/doctor/dashboard.blade.php ENDPATH**/ ?>