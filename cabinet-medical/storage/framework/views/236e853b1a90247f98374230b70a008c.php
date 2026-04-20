<?php $__env->startSection('title', 'Modifier le dossier patient'); ?>
<?php $__env->startSection('page-title', 'Modifier le dossier patient'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-xl-9">
<form action="<?php echo e(route('patients.update', $patient)); ?>" method="POST">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <div class="row g-3">
        
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-person me-2 text-primary"></i>Informations personnelles</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('name', $patient->user->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Téléphone</label>
                            <input type="text" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('phone', $patient->user->phone)); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Date de naissance</label>
                            <input type="date" name="birth_date" class="form-control <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('birth_date', $patient->birth_date?->format('Y-m-d'))); ?>">
                            <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Sexe</label>
                            <select name="gender" class="form-select <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Non renseigné</option>
                                <option value="male"   <?php echo e(old('gender', $patient->gender) === 'male'   ? 'selected' : ''); ?>>Homme</option>
                                <option value="female" <?php echo e(old('gender', $patient->gender) === 'female' ? 'selected' : ''); ?>>Femme</option>
                            </select>
                            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Groupe sanguin</label>
                            <select name="blood_type" class="form-select <?php $__errorArgs = ['blood_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Non renseigné</option>
                                <?php $__currentLoopData = ['A+','A-','B+','B-','AB+','AB-','O+','O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($bt); ?>" <?php echo e(old('blood_type', $patient->blood_type) === $bt ? 'selected' : ''); ?>><?php echo e($bt); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['blood_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">CIN</label>
                            <input type="text" name="cin" class="form-control <?php $__errorArgs = ['cin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('cin', $patient->cin)); ?>" placeholder="AB123456">
                            <?php $__errorArgs = ['cin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">N° Assurance</label>
                            <input type="text" name="insurance_number" class="form-control"
                                   value="<?php echo e(old('insurance_number', $patient->insurance_number)); ?>" placeholder="CNSS-XXXX">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Adresse</label>
                            <textarea name="address" class="form-control" rows="2"
                                      placeholder="Adresse complète..."><?php echo e(old('address', $patient->address)); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-heart-pulse me-2 text-danger"></i>Informations médicales</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Allergies connues</label>
                            <textarea name="allergies" class="form-control" rows="2"
                                      placeholder="Ex: Pénicilline, Aspirine, Arachides..."><?php echo e(old('allergies', $patient->allergies)); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Maladies chroniques</label>
                            <textarea name="chronic_diseases" class="form-control" rows="2"
                                      placeholder="Ex: Diabète type 2, Hypertension..."><?php echo e(old('chronic_diseases', $patient->chronic_diseases)); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Antécédents médicaux</label>
                            <textarea name="medical_history" class="form-control" rows="3"
                                      placeholder="Chirurgies, hospitalisations, traitements passés..."><?php echo e(old('medical_history', $patient->medical_history)); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header py-3 px-4">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-telephone-fill me-2 text-warning"></i>Contact d'urgence</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom</label>
                        <input type="text" name="emergency_contact_name" class="form-control"
                               value="<?php echo e(old('emergency_contact_name', $patient->emergency_contact_name)); ?>"
                               placeholder="Nom du contact">
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="text" name="emergency_contact_phone" class="form-control"
                               value="<?php echo e(old('emergency_contact_phone', $patient->emergency_contact_phone)); ?>"
                               placeholder="06XXXXXXXX">
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                </button>
                <a href="<?php echo e(route('patients.show', $patient)); ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </div>
    </div>
</form>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/patients/edit.blade.php ENDPATH**/ ?>