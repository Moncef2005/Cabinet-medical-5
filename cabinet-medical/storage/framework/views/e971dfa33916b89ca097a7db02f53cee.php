<?php $__env->startSection('title', 'Dossier patient'); ?>
<?php $__env->startSection('page-title', 'Dossier médical'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body p-4 text-center">
                <div class="avatar mx-auto mb-3" style="width:72px;height:72px;font-size:1.8rem;background:<?php echo e($patient->gender === 'female' ? '#e91e8c' : '#1a6b9a'); ?>">
                    <?php echo e(strtoupper(substr($patient->user->name, 0, 1))); ?>

                </div>
                <h5 class="fw-bold mb-0"><?php echo e($patient->user->name); ?></h5>
                <div class="text-muted mb-3"><?php echo e($patient->user->email); ?></div>
                <?php if($patient->blood_type): ?>
                <span class="badge bg-danger fs-6 mb-3"><?php echo e($patient->blood_type); ?></span>
                <?php endif; ?>
                <div class="row g-2 text-start">
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="text-muted" style="font-size:.72rem">Âge</div>
                            <div class="fw-bold"><?php echo e($patient->age ?? '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="text-muted" style="font-size:.72rem">Sexe</div>
                            <div class="fw-bold"><?php echo e($patient->gender === 'male' ? '♂ Homme' : '♀ Femme'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">Informations</h6>
                <a href="<?php echo e(route('patients.edit', $patient)); ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
            </div>
            <div class="card-body p-4">
                <div class="row g-2" style="font-size:.85rem">
                    <?php if($patient->birth_date): ?>
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Naissance</span>
                        <span class="fw-semibold"><?php echo e($patient->birth_date->format('d/m/Y')); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($patient->cin): ?>
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">CIN</span>
                        <span class="fw-semibold"><?php echo e($patient->cin); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($patient->user->phone): ?>
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Téléphone</span>
                        <span class="fw-semibold"><?php echo e($patient->user->phone); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($patient->insurance_number): ?>
                    <div class="col-12 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Assurance</span>
                        <span class="fw-semibold"><?php echo e($patient->insurance_number); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($patient->address): ?>
                    <div class="col-12">
                        <span class="text-muted d-block mb-1">Adresse</span>
                        <span class="fw-semibold"><?php echo e($patient->address); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php if($patient->allergies || $patient->chronic_diseases): ?>
        <div class="card border-danger">
            <div class="card-header py-3 px-4 bg-danger bg-opacity-10">
                <h6 class="mb-0 fw-semibold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Alertes médicales</h6>
            </div>
            <div class="card-body p-4">
                <?php if($patient->allergies): ?>
                <div class="mb-2">
                    <div class="text-muted mb-1" style="font-size:.8rem">Allergies</div>
                    <div class="fw-semibold text-danger" style="font-size:.875rem"><?php echo e($patient->allergies); ?></div>
                </div>
                <?php endif; ?>
                <?php if($patient->chronic_diseases): ?>
                <div>
                    <div class="text-muted mb-1" style="font-size:.8rem">Maladies chroniques</div>
                    <div class="fw-semibold" style="font-size:.875rem"><?php echo e($patient->chronic_diseases); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-8">
        
        <?php if(!auth()->user()->isPatient()): ?>
        <div class="card mb-3">
            <div class="card-body p-3 d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('appointments.create', ['patient_id' => $patient->id])); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-calendar-plus me-1"></i>Nouveau rendez-vous
                </a>
                <?php if($patient->upcoming_appointment): ?>
                <a href="<?php echo e(route('appointments.show', $patient->upcoming_appointment)); ?>" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-calendar-check me-1"></i>Prochain RDV: <?php echo e($patient->upcoming_appointment->scheduled_at->format('d/m/Y H:i')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Historique des consultations</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $recentConsultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consult): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom">
                    <div class="text-center" style="min-width:50px">
                        <div class="fw-semibold text-primary" style="font-size:.8rem"><?php echo e($consult->created_at->format('d/m')); ?></div>
                        <div class="text-muted" style="font-size:.72rem"><?php echo e($consult->created_at->format('Y')); ?></div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem">Dr. <?php echo e($consult->doctor->user->name); ?></div>
                        <div class="text-muted" style="font-size:.8rem"><?php echo e($consult->doctor->specialty); ?></div>
                        <div style="font-size:.85rem;margin-top:4px"><?php echo e(\Illuminate\Support\Str::limit($consult->diagnosis, 80)); ?></div>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <a href="<?php echo e(route('consultations.show', $consult)); ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                        <?php if($consult->prescription): ?>
                        <a href="<?php echo e(route('prescriptions.pdf', $consult->prescription)); ?>" class="btn btn-sm btn-outline-danger">PDF</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-clipboard-x" style="font-size:2rem"></i>
                    <p class="mt-2 mb-0">Aucune consultation enregistrée</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header py-3 px-4">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Rendez-vous à venir</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div>
                        <div class="fw-semibold" style="font-size:.875rem"><?php echo e($appt->scheduled_at->format('d/m/Y H:i')); ?></div>
                        <div class="text-muted" style="font-size:.8rem">Dr. <?php echo e($appt->doctor->user->name); ?> · <?php echo e($appt->reason); ?></div>
                    </div>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <span class="badge bg-<?php echo e($appt->status_color); ?>-subtle text-<?php echo e($appt->status_color); ?> rounded-pill"><?php echo e($appt->status_label); ?></span>
                        <a href="<?php echo e(route('appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-secondary">Voir</a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4 text-muted" style="font-size:.875rem">Aucun rendez-vous à venir</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/patients/show.blade.php ENDPATH**/ ?>