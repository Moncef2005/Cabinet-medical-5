<?php $__env->startSection('title', 'Gestion des utilisateurs'); ?>
<?php $__env->startSection('page-title', 'Gestion des utilisateurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-people me-2 text-primary"></i>Utilisateurs</h6>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Nouvel utilisateur
        </a>
    </div>

    
    <div class="card-body border-bottom py-3 px-4">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher par nom, email..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Tous les rôles</option>
                    <option value="admin"     <?php echo e(request('role')=='admin'     ? 'selected':''); ?>>Administrateur</option>
                    <option value="doctor"    <?php echo e(request('role')=='doctor'    ? 'selected':''); ?>>Médecin</option>
                    <option value="secretary" <?php echo e(request('role')=='secretary' ? 'selected':''); ?>>Secrétaire</option>
                    <option value="patient"   <?php echo e(request('role')=='patient'   ? 'selected':''); ?>>Patient</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-secondary btn-sm w-100">Réinitialiser</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">Utilisateur</th>
                    <th>Rôle</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th class="px-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="background:<?php echo e($user->isDoctor() ? '#198754' : ($user->isAdmin() ? '#dc3545' : '#1a6b9a')); ?>">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem"><?php echo e($user->name); ?></div>
                                <div class="text-muted" style="font-size:.75rem"><?php echo e($user->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php
                            $roleColors = ['admin'=>'danger','doctor'=>'success','secretary'=>'warning','patient'=>'info'];
                            $color = $roleColors[$user->role] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?php echo e($color); ?>-subtle text-<?php echo e($color); ?> border border-<?php echo e($color); ?>-subtle rounded-pill px-3">
                            <?php echo e($user->role_label); ?>

                        </span>
                    </td>
                    <td style="font-size:.875rem"><?php echo e($user->phone ?? '—'); ?></td>
                    <td>
                        <?php if($user->is_active): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Actif</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Désactivé</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.875rem"><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                    <td class="px-4 text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if($user->isDoctor()): ?>
                            <a href="<?php echo e(route('admin.doctors.availability', $user->doctor)); ?>" class="btn btn-sm btn-outline-success" title="Disponibilités">
                                <i class="bi bi-calendar-week"></i>
                            </a>
                            <?php endif; ?>
                            <form action="<?php echo e(route('admin.users.toggle', $user)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-sm btn-outline-<?php echo e($user->is_active ? 'warning' : 'success'); ?>" title="<?php echo e($user->is_active ? 'Désactiver' : 'Activer'); ?>">
                                    <i class="bi bi-<?php echo e($user->is_active ? 'pause' : 'play'); ?>-circle"></i>
                                </button>
                            </form>
                            <?php if($user->id !== auth()->id()): ?>
                            <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="bi bi-people" style="font-size:2rem"></i><br>Aucun utilisateur trouvé
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($users->hasPages()): ?>
    <div class="card-footer py-3 px-4">
        <?php echo e($users->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/admin/users/index.blade.php ENDPATH**/ ?>