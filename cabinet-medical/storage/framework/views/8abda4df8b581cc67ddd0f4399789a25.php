<?php $__env->startSection('title', 'Mon espace patient'); ?>
<?php $__env->startSection('page-title', 'Mon espace patient'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    
    <div class="col-12">
        <div class="card border-0" style="background:linear-gradient(135deg,#1a6b9a,#4da3d4);color:#fff">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1">Bonjour, <?php echo e(auth()->user()->name); ?> 👋</h4>
                    <p class="mb-0 opacity-75">Bienvenue dans votre espace santé</p>
                </div>
                <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-light fw-semibold">
                    <i class="bi bi-calendar-plus me-1"></i> Prendre un RDV
                </a>
            </div>
        </div>
    </div>

    
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Mes prochains rendez-vous</h6>
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="avatar bg-primary">
                        <i class="bi bi-heart-pulse text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem">Dr. <?php echo e($appt->doctor->user->name); ?></div>
                        <div class="text-muted" style="font-size:.78rem">
                            <?php echo e($appt->doctor->specialty); ?> · <?php echo e($appt->scheduled_at->format('d/m/Y à H:i')); ?>

                        </div>
                        <div style="font-size:.78rem">Motif : <?php echo e($appt->reason); ?></div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="badge bg-<?php echo e($appt->status_color); ?>-subtle text-<?php echo e($appt->status_color); ?> rounded-pill"><?php echo e($appt->status_label); ?></span>
                        <a href="<?php echo e(route('appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-secondary">Détail</a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size:2.5rem"></i>
                    <p class="mt-2 mb-0">Aucun rendez-vous à venir</p>
                    <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-sm btn-primary mt-2">
                        <i class="bi bi-calendar-plus me-1"></i>Prendre un rendez-vous
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-person-circle me-2 text-primary"></i>Mon dossier</h6>
                <a href="<?php echo e(route('patients.edit', $patient)); ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
            </div>
            <div class="card-body px-4 py-3">
                <div class="row g-2" style="font-size:.85rem">
                    <div class="col-6">
                        <div class="text-muted mb-1">Date de naissance</div>
                        <div class="fw-semibold"><?php echo e($patient->birth_date ? $patient->birth_date->format('d/m/Y') : '—'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1">Groupe sanguin</div>
                        <div class="fw-semibold">
                            <?php if($patient->blood_type): ?>
                                <span class="badge bg-danger"><?php echo e($patient->blood_type); ?></span>
                            <?php else: ?> — <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted mb-1">Allergies</div>
                        <div class="fw-semibold"><?php echo e($patient->allergies ?? 'Aucune allergie connue'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Dernières consultations</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $recentConsultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consult): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="avatar bg-success"><i class="bi bi-clipboard2-check text-white"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem">Dr. <?php echo e($consult->doctor->user->name); ?></div>
                        <div class="text-muted" style="font-size:.78rem"><?php echo e($consult->created_at->format('d/m/Y')); ?></div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="<?php echo e(route('consultations.show', $consult)); ?>" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-eye"></i>
                        </a>
                        <?php if($consult->prescription): ?>
                        <a href="<?php echo e(route('prescriptions.pdf', $consult->prescription)); ?>" class="btn btn-sm btn-outline-danger" title="Télécharger ordonnance">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4 text-muted" style="font-size:.875rem">
                    <i class="bi bi-clipboard-x me-1"></i> Aucune consultation enregistrée
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/patient/dashboard.blade.php ENDPATH**/ ?>