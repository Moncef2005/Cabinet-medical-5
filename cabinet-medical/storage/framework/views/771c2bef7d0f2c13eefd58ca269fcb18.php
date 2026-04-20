<?php $__env->startSection('title', 'Détail rendez-vous'); ?>
<?php $__env->startSection('page-title', 'Détail du rendez-vous'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Rendez-vous #<?php echo e($appointment->id); ?></h6>
                <span class="badge bg-<?php echo e($appointment->status_color); ?>-subtle text-<?php echo e($appointment->status_color); ?> border border-<?php echo e($appointment->status_color); ?>-subtle rounded-pill px-3 py-2">
                    <?php echo e($appointment->status_label); ?>

                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Patient</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar" style="width:48px;height:48px;font-size:1.1rem">
                                <?php echo e(strtoupper(substr($appointment->patient->user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div class="fw-semibold"><?php echo e($appointment->patient->user->name); ?></div>
                                <div class="text-muted" style="font-size:.85rem"><?php echo e($appointment->patient->user->email); ?></div>
                                <div class="text-muted" style="font-size:.85rem"><?php echo e($appointment->patient->user->phone); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Médecin</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar" style="width:48px;height:48px;font-size:1.1rem;background:#198754">
                                <?php echo e(strtoupper(substr($appointment->doctor->user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div class="fw-semibold">Dr. <?php echo e($appointment->doctor->user->name); ?></div>
                                <div class="text-muted" style="font-size:.85rem"><?php echo e($appointment->doctor->specialty); ?></div>
                                <div class="text-muted" style="font-size:.85rem"><?php echo e($appointment->doctor->consultation_price); ?> DH</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Date & Heure</h6>
                        <div class="fw-semibold"><?php echo e($appointment->scheduled_at->format('d/m/Y')); ?></div>
                        <div class="text-muted"><?php echo e($appointment->scheduled_at->format('H:i')); ?> – <?php echo e($appointment->end_time->format('H:i')); ?> (<?php echo e($appointment->duration); ?> min)</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Motif</h6>
                        <div class="fw-semibold"><?php echo e($appointment->reason); ?></div>
                    </div>
                    <?php if($appointment->notes): ?>
                    <div class="col-12">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Notes</h6>
                        <div class="bg-light rounded p-3" style="font-size:.875rem"><?php echo e($appointment->notes); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($appointment->cancellation_reason): ?>
                    <div class="col-12">
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size:.72rem;letter-spacing:.08em">Motif d'annulation</h6>
                        <div class="bg-danger bg-opacity-10 rounded p-3 text-danger" style="font-size:.875rem"><?php echo e($appointment->cancellation_reason); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer px-4 py-3 d-flex gap-2 flex-wrap">
                <?php if($appointment->canBeConfirmed() && (auth()->user()->isAdmin() || auth()->user()->isSecretary())): ?>
                <form action="<?php echo e(route('appointments.confirm', $appointment)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Confirmer</button>
                </form>
                <?php endif; ?>
                <?php if(auth()->user()->isDoctor() && $appointment->status === 'confirmed' && !$appointment->consultation): ?>
                <a href="<?php echo e(route('consultations.create', $appointment)); ?>" class="btn btn-primary">
                    <i class="bi bi-clipboard-plus me-1"></i>Démarrer la consultation
                </a>
                <?php endif; ?>
                <?php if($appointment->consultation): ?>
                <a href="<?php echo e(route('consultations.show', $appointment->consultation)); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-clipboard2-pulse me-1"></i>Voir la consultation
                </a>
                <?php endif; ?>
                <?php if($appointment->canBeCancelled()): ?>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="bi bi-x-lg me-1"></i>Annuler
                </button>
                <?php endif; ?>
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-outline-secondary ms-auto">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
            </div>
        </div>
    </div>

    
    <?php if($appointment->consultation): ?>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Consultation</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="text-muted mb-1" style="font-size:.8rem">Diagnostic</div>
                    <div class="fw-semibold" style="font-size:.875rem"><?php echo e($appointment->consultation->diagnosis); ?></div>
                </div>
                <?php if($appointment->consultation->prescription): ?>
                <div class="mb-3">
                    <div class="text-muted mb-1" style="font-size:.8rem">Ordonnance</div>
                    <div class="fw-semibold text-success" style="font-size:.875rem">
                        <i class="bi bi-check-circle me-1"></i><?php echo e($appointment->consultation->prescription->items->count()); ?> médicament(s)
                    </div>
                </div>
                <a href="<?php echo e(route('prescriptions.pdf', $appointment->consultation->prescription)); ?>" class="btn btn-danger btn-sm w-100">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Télécharger l'ordonnance (PDF)
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>


<?php if($appointment->canBeCancelled()): ?>
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annuler le rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('appointments.cancel', $appointment)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="modal-body">
                    <p>Confirmer l'annulation du rendez-vous du <strong><?php echo e($appointment->scheduled_at->format('d/m/Y à H:i')); ?></strong> ?</p>
                    <div class="mb-3">
                        <label class="form-label">Motif d'annulation</label>
                        <textarea name="cancellation_reason" class="form-control" rows="2" placeholder="Raison de l'annulation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/appointments/show.blade.php ENDPATH**/ ?>