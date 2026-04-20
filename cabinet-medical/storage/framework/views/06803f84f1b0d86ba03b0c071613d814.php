<?php $__env->startSection('title', 'Tableau de bord secrétaire'); ?>
<?php $__env->startSection('page-title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a6b9a,#2196f3)">
            <div class="stat-icon"><i class="bi bi-calendar-day"></i></div>
            <div><div class="stat-value"><?php echo e($stats['today_total']); ?></div><div class="stat-label">RDV aujourd'hui</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#ffc107,#ff9800)">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value"><?php echo e($stats['today_pending']); ?></div><div class="stat-label">En attente de confirmation</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#198754,#4caf50)">
            <div class="stat-icon"><i class="bi bi-person-heart"></i></div>
            <div><div class="stat-value"><?php echo e($stats['total_patients']); ?></div><div class="stat-label">Total patients</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6f42c1,#9c27b0)">
            <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
            <div><div class="stat-value"><?php echo e($stats['total_doctors']); ?></div><div class="stat-label">Médecins</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-day me-2 text-primary"></i>Planning du jour — <?php echo e(now()->format('d/m/Y')); ?></h6>
                <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Nouveau RDV
                </a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $todayAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="text-center" style="min-width:55px">
                        <div class="fw-bold text-primary"><?php echo e($appt->scheduled_at->format('H:i')); ?></div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem"><?php echo e($appt->patient->user->name); ?></div>
                        <div class="text-muted" style="font-size:.78rem">
                            Dr. <?php echo e($appt->doctor->user->name); ?> · <?php echo e($appt->doctor->specialty); ?>

                        </div>
                    </div>
                    <div class="d-flex gap-1 align-items-center">
                        <span class="badge bg-<?php echo e($appt->status_color); ?>-subtle text-<?php echo e($appt->status_color); ?> rounded-pill">
                            <?php echo e($appt->status_label); ?>

                        </span>
                        <?php if($appt->canBeConfirmed()): ?>
                        <form action="<?php echo e(route('appointments.confirm', $appt)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button class="btn btn-sm btn-success" title="Confirmer"><i class="bi bi-check-lg"></i></button>
                        </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size:2.5rem"></i>
                    <p class="mt-2 mb-0">Aucun rendez-vous aujourd'hui</p>
                    <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-sm btn-primary mt-2">Créer un RDV</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-hourglass-split me-2 text-warning"></i>En attente de confirmation</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $pendingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="avatar"><?php echo e(strtoupper(substr($appt->patient->user->name, 0, 1))); ?></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem"><?php echo e($appt->patient->user->name); ?></div>
                        <div class="text-muted" style="font-size:.78rem"><?php echo e($appt->scheduled_at->format('d/m/Y H:i')); ?></div>
                    </div>
                    <form action="<?php echo e(route('appointments.confirm', $appt)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button class="btn btn-sm btn-success"><i class="bi bi-check-lg me-1"></i>Confirmer</button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4 text-muted" style="font-size:.875rem">
                    <i class="bi bi-check-circle me-1 text-success"></i> Aucun RDV en attente
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card mt-3">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2 text-warning"></i>Actions rapides</h6>
            </div>
            <div class="card-body d-grid gap-2 p-3">
                <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-calendar-plus me-2"></i>Nouveau rendez-vous
                </a>
                <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-person-heart me-2"></i>Liste des patients
                </a>
                <a href="<?php echo e(route('appointments.calendar')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-calendar3 me-2"></i>Calendrier
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/secretary/dashboard.blade.php ENDPATH**/ ?>