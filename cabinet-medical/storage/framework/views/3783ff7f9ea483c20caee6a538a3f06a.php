
<?php $__env->startSection('title', 'Consultation'); ?>
<?php $__env->startSection('page-title', 'Détail de la consultation'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-8">
        
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Consultation — <?php echo e($consultation->created_at->format('d/m/Y')); ?></h6>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('consultations.pdf', $consultation)); ?>" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                    </a>
                    <?php if(auth()->user()->isDoctor() && $consultation->doctor_id === auth()->user()->doctor->id): ?>
                    <a href="<?php echo e(route('consultations.edit', $consultation)); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Médecin</div>
                        <div class="fw-semibold">Dr. <?php echo e($consultation->doctor->user->name); ?></div>
                        <div class="text-muted" style="font-size:.85rem"><?php echo e($consultation->doctor->specialty); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Patient</div>
                        <div class="fw-semibold"><?php echo e($consultation->patient->user->name); ?></div>
                        <div class="text-muted" style="font-size:.85rem"><?php echo e($consultation->patient->user->phone); ?></div>
                    </div>
                    <div class="col-12"><hr class="my-1"></div>
                    <div class="col-12">
                        <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Motif principal</div>
                        <div class="fw-semibold"><?php echo e($consultation->chief_complaint); ?></div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Diagnostic</div>
                        <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded p-3">
                            <?php echo e($consultation->diagnosis); ?>

                        </div>
                    </div>
                    <?php if($consultation->notes): ?>
                    <div class="col-12">
                        <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Notes & observations</div>
                        <div class="bg-light rounded p-3" style="font-size:.875rem"><?php echo e($consultation->notes); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php if($consultation->prescription): ?>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-capsule me-2 text-danger"></i>Ordonnance</h6>
                <a href="<?php echo e(route('prescriptions.pdf', $consultation->prescription)); ?>" class="btn btn-sm btn-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Télécharger PDF
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">Médicament</th>
                                <th>Dosage</th>
                                <th>Fréquence</th>
                                <th>Durée</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $consultation->prescription->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 fw-semibold" style="font-size:.875rem"><?php echo e($item->medication); ?></td>
                                <td style="font-size:.875rem"><?php echo e($item->dosage); ?></td>
                                <td style="font-size:.875rem"><?php echo e($item->frequency); ?></td>
                                <td style="font-size:.875rem"><?php echo e($item->duration); ?></td>
                                <td class="text-muted" style="font-size:.875rem"><?php echo e($item->instructions ?? '—'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($consultation->prescription->notes): ?>
                <div class="p-4 pt-2 text-muted border-top" style="font-size:.875rem">
                    <strong>Notes :</strong> <?php echo e($consultation->prescription->notes); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-activity me-2 text-danger"></i>Signes vitaux</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php if($consultation->weight): ?>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="text-muted" style="font-size:.75rem">Poids</div>
                            <div class="fw-bold fs-5"><?php echo e($consultation->weight); ?></div>
                            <div class="text-muted" style="font-size:.75rem">kg</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($consultation->height): ?>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="text-muted" style="font-size:.75rem">Taille</div>
                            <div class="fw-bold fs-5"><?php echo e($consultation->height); ?></div>
                            <div class="text-muted" style="font-size:.75rem">cm</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($consultation->bmi): ?>
                    <div class="col-12">
                        <div class="text-center p-2 bg-primary bg-opacity-10 rounded">
                            <div class="text-muted" style="font-size:.75rem">IMC</div>
                            <div class="fw-bold fs-5 text-primary"><?php echo e($consultation->bmi); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($consultation->blood_pressure_systolic): ?>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
                            <span class="text-muted" style="font-size:.8rem"><i class="bi bi-heart-pulse text-danger me-1"></i>Tension</span>
                            <span class="fw-bold"><?php echo e($consultation->blood_pressure); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($consultation->temperature): ?>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="text-muted" style="font-size:.75rem">Température</div>
                            <div class="fw-bold"><?php echo e($consultation->temperature); ?>°C</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($consultation->heart_rate): ?>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="text-muted" style="font-size:.75rem">Pouls</div>
                            <div class="fw-bold"><?php echo e($consultation->heart_rate); ?> bpm</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-cash me-2 text-success"></i>Paiement</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted">Montant</span>
                    <span class="fw-bold fs-5"><?php echo e($consultation->price); ?> DH</span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted">Statut</span>
                    <?php $payColors = ['paid'=>'success','pending'=>'warning','insurance'=>'info']; $payLabels=['paid'=>'Payé','pending'=>'En attente','insurance'=>'Assurance']; ?>
                    <span class="badge bg-<?php echo e($payColors[$consultation->payment_status]); ?>-subtle text-<?php echo e($payColors[$consultation->payment_status]); ?> border rounded-pill px-3">
                        <?php echo e($payLabels[$consultation->payment_status]); ?>

                    </span>
                </div>
            </div>
        </div>

        <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-outline-secondary w-100 mt-3">
            <i class="bi bi-arrow-left me-1"></i>Retour aux rendez-vous
        </a>
    </div>
</div>
</form> <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/consultations/edit.blade.php ENDPATH**/ ?>