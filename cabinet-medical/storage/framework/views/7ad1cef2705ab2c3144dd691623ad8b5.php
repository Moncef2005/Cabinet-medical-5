<?php $__env->startSection('title', 'Nouveau rendez-vous'); ?>
<?php $__env->startSection('page-title', 'Prendre un rendez-vous'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-plus me-2 text-primary"></i>Nouveau rendez-vous</h6>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('appointments.store')); ?>" method="POST" id="apptForm">
            <?php echo csrf_field(); ?>

            <?php if(!auth()->user()->isPatient()): ?>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-select <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Sélectionner un patient...</option>
                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($pat->id); ?>" <?php echo e(old('patient_id')==$pat->id ? 'selected':''); ?>>
                        <?php echo e($pat->user->name); ?> — <?php echo e($pat->user->phone); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <?php else: ?>
            <input type="hidden" name="patient_id" value="<?php echo e(auth()->user()->patient->id); ?>">
            <?php endif; ?>

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Médecin <span class="text-danger">*</span></label>
                <select name="doctor_id" class="form-select <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="doctorSelect" required>
                    <option value="">Sélectionner un médecin...</option>
                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($doc->id); ?>" <?php echo e(old('doctor_id', $selectedDoctor?->id)==$doc->id ? 'selected':''); ?>>
                        Dr. <?php echo e($doc->user->name); ?> — <?php echo e($doc->specialty); ?> (<?php echo e($doc->consultation_price); ?> DH)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                <input type="date" id="dateInput" class="form-control" min="<?php echo e(now()->addDay()->format('Y-m-d')); ?>" value="<?php echo e(old('date')); ?>">
            </div>

            
            <div class="mb-4" id="slotsSection" style="display:none">
                <label class="form-label fw-semibold">Heure disponible <span class="text-danger">*</span></label>
                <input type="hidden" name="scheduled_at" id="scheduledAt" value="<?php echo e(old('scheduled_at')); ?>">
                <div id="slotsContainer" class="d-flex flex-wrap gap-2">
                    <span class="text-muted">Chargement...</span>
                </div>
                <?php $__errorArgs = ['scheduled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger mt-1" style="font-size:.85rem"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Motif de consultation <span class="text-danger">*</span></label>
                <input type="text" name="reason" class="form-control <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('reason')); ?>" placeholder="Ex: Douleur thoracique, Fièvre, Suivi traitement..." required>
                <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Notes additionnelles</label>
                <textarea name="notes" class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"
                          placeholder="Informations supplémentaires..."><?php echo e(old('notes')); ?></textarea>
                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-calendar-check me-1"></i>Confirmer le rendez-vous</button>
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const doctorSelect = document.getElementById('doctorSelect');
const dateInput    = document.getElementById('dateInput');
const slotsSection = document.getElementById('slotsSection');
const slotsContainer = document.getElementById('slotsContainer');
const scheduledAt  = document.getElementById('scheduledAt');

function loadSlots() {
    const doctorId = doctorSelect.value;
    const date     = dateInput.value;
    if (!doctorId || !date) { slotsSection.style.display = 'none'; return; }

    slotsSection.style.display = 'block';
    slotsContainer.innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2"></div> Chargement des créneaux...';

    fetch(`<?php echo e(route('appointments.slots')); ?>?doctor_id=${doctorId}&date=${date}`)
        .then(r => r.json())
        .then(data => {
            if (!data.slots || data.slots.length === 0) {
                slotsContainer.innerHTML = '<div class="alert alert-warning mb-0 py-2">Aucun créneau disponible pour cette date. Veuillez choisir une autre date.</div>';
                return;
            }
            slotsContainer.innerHTML = '';
            data.slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-primary';
                btn.textContent = slot;
                btn.dataset.slot = `${date} ${slot}:00`;
                btn.addEventListener('click', () => {
                    document.querySelectorAll('#slotsContainer button').forEach(b => b.classList.remove('btn-primary', 'active'));
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary', 'active');
                    scheduledAt.value = btn.dataset.slot;
                });
                slotsContainer.appendChild(btn);
            });
        })
        .catch(() => {
            slotsContainer.innerHTML = '<div class="alert alert-danger mb-0 py-2">Erreur lors du chargement des créneaux.</div>';
        });
}

doctorSelect.addEventListener('change', loadSlots);
dateInput.addEventListener('change', loadSlots);

// Auto-load if values already set
if (doctorSelect.value && dateInput.value) loadSlots();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/appointments/create.blade.php ENDPATH**/ ?>