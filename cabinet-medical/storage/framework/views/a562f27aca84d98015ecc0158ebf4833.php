<?php $__env->startSection('title', 'Nouvelle consultation'); ?>
<?php $__env->startSection('page-title', 'Créer une consultation'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-xl-10">

<div class="card mb-3 border-0" style="background:linear-gradient(135deg,#198754,#4caf50);color:#fff">
    <div class="card-body p-4 d-flex align-items-center gap-4">
        <div class="avatar" style="width:56px;height:56px;font-size:1.4rem;background:rgba(255,255,255,.2)">
            <?php echo e(strtoupper(substr($appointment->patient->user->name, 0, 1))); ?>

        </div>
        <div>
            <h5 class="mb-0 fw-bold"><?php echo e($appointment->patient->user->name); ?></h5>
            <div class="opacity-75" style="font-size:.875rem">
                RDV du <?php echo e($appointment->scheduled_at->format('d/m/Y à H:i')); ?> · Motif : <?php echo e($appointment->reason); ?>

                <?php if($appointment->patient->age): ?>
                · <?php echo e($appointment->patient->age); ?> ans · <?php echo e($appointment->patient->blood_type); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo e(route('consultations.store', $appointment)); ?>" method="POST" id="consultationForm">
    <?php echo csrf_field(); ?>

    <div class="row g-3">
        
        <div class="col-lg-8">

            
            <div class="card mb-3">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Consultation</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motif principal <span class="text-danger">*</span></label>
                        <input type="text" name="chief_complaint" class="form-control <?php $__errorArgs = ['chief_complaint'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('chief_complaint', $appointment->reason)); ?>" required>
                        <?php $__errorArgs = ['chief_complaint'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Diagnostic <span class="text-danger">*</span></label>
                        <textarea name="diagnosis" class="form-control <?php $__errorArgs = ['diagnosis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"
                                  placeholder="Diagnostic médical..." required><?php echo e(old('diagnosis')); ?></textarea>
                        <?php $__errorArgs = ['diagnosis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Notes & observations</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Observations cliniques, recommandations..."><?php echo e(old('notes')); ?></textarea>
                    </div>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-capsule me-2 text-danger"></i>Ordonnance</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="addMedication">
                        <i class="bi bi-plus-lg me-1"></i>Ajouter un médicament
                    </button>
                </div>
                <div class="card-body p-4">
                    <div id="medicationsContainer">
                        
                        <div class="medication-row border rounded p-3 mb-3 position-relative" id="med_0">
                            <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 remove-med" style="border-radius:50%">
                                <i class="bi bi-x"></i>
                            </button>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:.82rem">Médicament <span class="text-danger">*</span></label>
                                    <input type="text" name="medications[0][medication]" class="form-control form-control-sm" placeholder="Ex: Paracétamol 500mg" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:.82rem">Dosage <span class="text-danger">*</span></label>
                                    <input type="text" name="medications[0][dosage]" class="form-control form-control-sm" placeholder="Ex: 1 comprimé" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" style="font-size:.82rem">Fréquence <span class="text-danger">*</span></label>
                                    <select name="medications[0][frequency]" class="form-select form-select-sm" required>
                                        <option>1 fois/jour</option><option>2 fois/jour</option>
                                        <option selected>3 fois/jour</option><option>Matin et soir</option>
                                        <option>Si douleur</option><option>Avant les repas</option>
                                        <option>Après les repas</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" style="font-size:.82rem">Durée <span class="text-danger">*</span></label>
                                    <select name="medications[0][duration]" class="form-select form-select-sm" required>
                                        <option>3 jours</option><option>5 jours</option>
                                        <option selected>7 jours</option><option>10 jours</option>
                                        <option>15 jours</option><option>1 mois</option><option>3 mois</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" style="font-size:.82rem">Instructions</label>
                                    <input type="text" name="medications[0][instructions]" class="form-control form-control-sm" placeholder="Avec repas, à jeun...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Notes de l'ordonnance</label>
                        <textarea name="prescription_notes" class="form-control form-control-sm" rows="2" placeholder="Recommandations générales..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-activity me-2 text-danger"></i>Signes vitaux</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Poids (kg)</label>
                            <input type="number" name="weight" class="form-control form-control-sm" placeholder="70" min="1" max="500" step="0.1" value="<?php echo e(old('weight')); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Taille (cm)</label>
                            <input type="number" name="height" class="form-control form-control-sm" placeholder="170" min="30" max="250" value="<?php echo e(old('height')); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Tension artérielle</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="blood_pressure_systolic" class="form-control" placeholder="120" min="50" max="300" value="<?php echo e(old('blood_pressure_systolic')); ?>">
                                <span class="input-group-text">/</span>
                                <input type="number" name="blood_pressure_diastolic" class="form-control" placeholder="80" min="20" max="200" value="<?php echo e(old('blood_pressure_diastolic')); ?>">
                                <span class="input-group-text">mmHg</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Température (°C)</label>
                            <input type="number" name="temperature" class="form-control form-control-sm" placeholder="37.0" min="34" max="42" step="0.1" value="<?php echo e(old('temperature')); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Pouls (bpm)</label>
                            <input type="number" name="heart_rate" class="form-control form-control-sm" placeholder="72" min="20" max="300" value="<?php echo e(old('heart_rate')); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-cash me-2 text-success"></i>Paiement</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Montant (DH)</label>
                        <input type="number" name="price" class="form-control form-control-sm"
                               value="<?php echo e(old('price', $appointment->doctor->consultation_price)); ?>" min="0" step="10">
                    </div>
                    <div>
                        <label class="form-label fw-semibold" style="font-size:.82rem">Statut paiement <span class="text-danger">*</span></label>
                        <select name="payment_status" class="form-select form-select-sm" required>
                            <option value="pending"   <?php echo e(old('payment_status')==='pending'   ?'selected':''); ?>>En attente</option>
                            <option value="paid"      <?php echo e(old('payment_status')==='paid'      ?'selected':''); ?>>Payé</option>
                            <option value="insurance" <?php echo e(old('payment_status')==='insurance' ?'selected':''); ?>>Assurance</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle me-1"></i>Enregistrer la consultation
                </button>
                <a href="<?php echo e(route('appointments.show', $appointment)); ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </div>
    </div>
</form>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let medCount = 1;
document.getElementById('addMedication').addEventListener('click', function() {
    const i = medCount++;
    const template = `
    <div class="medication-row border rounded p-3 mb-3 position-relative" id="med_${i}">
        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 remove-med" style="border-radius:50%"><i class="bi bi-x"></i></button>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label fw-semibold" style="font-size:.82rem">Médicament <span class="text-danger">*</span></label>
                <input type="text" name="medications[${i}][medication]" class="form-control form-control-sm" placeholder="Ex: Amoxicilline 500mg" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold" style="font-size:.82rem">Dosage <span class="text-danger">*</span></label>
                <input type="text" name="medications[${i}][dosage]" class="form-control form-control-sm" placeholder="Ex: 1 comprimé" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:.82rem">Fréquence <span class="text-danger">*</span></label>
                <select name="medications[${i}][frequency]" class="form-select form-select-sm" required>
                    <option>1 fois/jour</option><option>2 fois/jour</option><option>3 fois/jour</option>
                    <option>Matin et soir</option><option>Si douleur</option><option>Après les repas</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:.82rem">Durée <span class="text-danger">*</span></label>
                <select name="medications[${i}][duration]" class="form-select form-select-sm" required>
                    <option>3 jours</option><option>5 jours</option><option>7 jours</option>
                    <option>10 jours</option><option>1 mois</option><option>3 mois</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:.82rem">Instructions</label>
                <input type="text" name="medications[${i}][instructions]" class="form-control form-control-sm" placeholder="Avec repas, à jeun...">
            </div>
        </div>
    </div>`;
    document.getElementById('medicationsContainer').insertAdjacentHTML('beforeend', template);
    attachRemove();
});

function attachRemove() {
    document.querySelectorAll('.remove-med').forEach(btn => {
        btn.onclick = function() {
            const rows = document.querySelectorAll('.medication-row');
            if (rows.length > 1) btn.closest('.medication-row').remove();
        };
    });
}
attachRemove();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/consultations/create.blade.php ENDPATH**/ ?>