<?php $__env->startSection('title', 'Rendez-vous'); ?>
<?php $__env->startSection('page-title', 'Liste des rendez-vous'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4 flex-wrap gap-2">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Rendez-vous</h6>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('appointments.calendar')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-calendar-week me-1"></i>Calendrier
            </a>
            <?php if(!auth()->user()->isDoctor()): ?>
            <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Nouveau RDV
            </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card-body border-bottom py-3 px-4">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Tous les statuts</option>
                    <option value="pending"   <?php echo e(request('status')=='pending'   ?'selected':''); ?>>En attente</option>
                    <option value="confirmed" <?php echo e(request('status')=='confirmed' ?'selected':''); ?>>Confirmé</option>
                    <option value="completed" <?php echo e(request('status')=='completed' ?'selected':''); ?>>Terminé</option>
                    <option value="cancelled" <?php echo e(request('status')=='cancelled' ?'selected':''); ?>>Annulé</option>
                    <option value="no_show"   <?php echo e(request('status')=='no_show'   ?'selected':''); ?>>Absent</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control form-control-sm" value="<?php echo e(request('date')); ?>">
            </div>
            <?php if(!auth()->user()->isDoctor()): ?>
            <div class="col-md-3">
                <select name="doctor_id" class="form-select form-select-sm">
                    <option value="">Tous les médecins</option>
                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($doc->id); ?>" <?php echo e(request('doctor_id')==$doc->id ?'selected':''); ?>>Dr. <?php echo e($doc->user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit"><i class="bi bi-search me-1"></i>Filtrer</button>
            </div>
            <div class="col-md-1">
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-outline-secondary btn-sm w-100" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
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
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                    <td><span style="font-size:.875rem"><?php echo e(\Illuminate\Support\Str::limit($appt->reason, 35)); ?></span></td>
                    <td>
                        <span class="badge bg-<?php echo e($appt->status_color); ?>-subtle text-<?php echo e($appt->status_color); ?> border border-<?php echo e($appt->status_color); ?>-subtle rounded-pill px-3">
                            <?php echo e($appt->status_label); ?>

                        </span>
                    </td>
                    <td class="px-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="<?php echo e(route('appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-secondary" title="Voir"><i class="bi bi-eye"></i></a>
                            <?php if($appt->canBeConfirmed() && (auth()->user()->isAdmin() || auth()->user()->isSecretary())): ?>
                            <form action="<?php echo e(route('appointments.confirm', $appt)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-sm btn-success" title="Confirmer"><i class="bi bi-check-lg"></i></button>
                            </form>
                            <?php endif; ?>
                            <?php if(auth()->user()->isDoctor() && $appt->status === 'confirmed' && !$appt->consultation): ?>
                            <a href="<?php echo e(route('consultations.create', $appt)); ?>" class="btn btn-sm btn-primary" title="Créer consultation"><i class="bi bi-clipboard-plus"></i></a>
                            <?php endif; ?>
                            <?php if($appt->canBeCancelled()): ?>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo e($appt->id); ?>" title="Annuler">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                
                <?php if($appt->canBeCancelled()): ?>
                <div class="modal fade" id="cancelModal<?php echo e($appt->id); ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Annuler le rendez-vous</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="<?php echo e(route('appointments.cancel', $appt)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir annuler ce rendez-vous avec <strong><?php echo e($appt->patient->user->name); ?></strong> le <?php echo e($appt->scheduled_at->format('d/m/Y à H:i')); ?> ?</p>
                                    <div class="mb-3">
                                        <label class="form-label">Motif d'annulation (optionnel)</label>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size:2.5rem;display:block;margin-bottom:.5rem"></i>
                    Aucun rendez-vous trouvé
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($appointments->hasPages()): ?>
    <div class="card-footer py-3 px-4"><?php echo e($appointments->withQueryString()->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/appointments/index.blade.php ENDPATH**/ ?>