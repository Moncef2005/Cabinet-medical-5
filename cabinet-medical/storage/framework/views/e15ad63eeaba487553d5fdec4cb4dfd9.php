<?php $__env->startSection('title', 'Patients'); ?>
<?php $__env->startSection('page-title', 'Liste des patients'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-person-heart me-2 text-primary"></i>Patients (<?php echo e($patients->total()); ?>)</h6>
    </div>
    <div class="card-body border-bottom py-3 px-4">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher par nom, email, téléphone, CIN..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="blood_type" class="form-select form-select-sm">
                    <option value="">Tous les groupes sanguins</option>
                    <?php $__currentLoopData = ['A+','A-','B+','B-','AB+','AB-','O+','O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($bt); ?>" <?php echo e(request('blood_type')===$bt ? 'selected':''); ?>><?php echo e($bt); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit"><i class="bi bi-search me-1"></i>Filtrer</button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-outline-secondary btn-sm w-100">Réinitialiser</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">Patient</th>
                    <th>Âge / Sexe</th>
                    <th>Groupe sanguin</th>
                    <th>Téléphone</th>
                    <th>Dernière consultation</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="background:<?php echo e($patient->gender === 'female' ? '#e91e8c' : '#1a6b9a'); ?>">
                                <?php echo e(strtoupper(substr($patient->user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem"><?php echo e($patient->user->name); ?></div>
                                <div class="text-muted" style="font-size:.75rem"><?php echo e($patient->user->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem">
                        <?php echo e($patient->age ? $patient->age . ' ans' : '—'); ?>

                        <span class="text-muted">· <?php echo e($patient->gender_label); ?></span>
                    </td>
                    <td>
                        <?php if($patient->blood_type): ?>
                            <span class="badge bg-danger rounded-pill"><?php echo e($patient->blood_type); ?></span>
                        <?php else: ?> —
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.875rem"><?php echo e($patient->user->phone ?? '—'); ?></td>
                    <td style="font-size:.875rem">
                        <?php if($patient->last_consultation): ?>
                            <?php echo e($patient->last_consultation->created_at->format('d/m/Y')); ?>

                            <div class="text-muted" style="font-size:.75rem">Dr. <?php echo e($patient->last_consultation->doctor->user->name); ?></div>
                        <?php else: ?>
                            <span class="text-muted">Jamais</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="<?php echo e(route('patients.show', $patient)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="<?php echo e(route('appointments.create', ['patient_id' => $patient->id])); ?>" class="btn btn-sm btn-outline-success" title="Nouveau RDV"><i class="bi bi-calendar-plus"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-5 text-muted">Aucun patient trouvé</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($patients->hasPages()): ?>
    <div class="card-footer py-3 px-4"><?php echo e($patients->withQueryString()->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/patients/index.blade.php ENDPATH**/ ?>