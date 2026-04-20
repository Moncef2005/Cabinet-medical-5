<?php $__env->startSection('title', 'Connexion'); ?>
<?php $__env->startSection('subtitle', 'Connectez-vous à votre espace'); ?>
<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('login')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Adresse email</label>
        <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" placeholder="votre@email.com" required autofocus>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold" style="font-size:.85rem">Mot de passe</label>
        <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="••••••••" required>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember" style="font-size:.85rem">Se souvenir de moi</label>
        </div>
        <a href="<?php echo e(route('password.request')); ?>" style="font-size:.85rem;color:var(--primary)">Mot de passe oublié ?</a>
    </div>
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
    </button>
    <p class="text-center text-muted mb-4" style="font-size:.85rem">
        Pas encore de compte ? <a href="<?php echo e(route('register')); ?>" style="color:var(--primary);font-weight:600">S'inscrire</a>
    </p>
</form>
<div class="demo-creds">
    <p class="fw-semibold mb-2" style="font-size:.8rem;color:#4a5568">🔑 Comptes de démo (mot de passe: password)</p>
    <div class="row g-1" style="font-size:.75rem">
        <div class="col-6"><code>admin@cabinet.ma</code><br><span class="text-muted">Administrateur</span></div>
        <div class="col-6"><code>dr.alami@cabinet.ma</code><br><span class="text-muted">Médecin</span></div>
        <div class="col-6 mt-1"><code>secretaire@cabinet.ma</code><br><span class="text-muted">Secrétaire</span></div>
        <div class="col-6 mt-1"><code>m.berrada@gmail.com</code><br><span class="text-muted">Patient</span></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/auth/login.blade.php ENDPATH**/ ?>