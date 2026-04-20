<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Cabinet Médical'); ?> | Cabinet Médical</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1a6b9a;
            --primary-dark: #145580;
            --sidebar-width: 260px;
            --sidebar-bg: #1a1f36;
            --sidebar-text: #a0aec0;
            --sidebar-active: #ffffff;
            --sidebar-hover-bg: rgba(255,255,255,0.08);
        }

        body { background: #f4f7fa; font-family: 'Segoe UI', system-ui, sans-serif; }

        /* ─── SIDEBAR ─────────────────────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-width); height: 100vh;
            background: var(--sidebar-bg);
            overflow-y: auto; z-index: 1000;
            transition: transform 0.3s ease;
            display: flex; flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand h5 { color: #fff; font-weight: 700; margin: 0; font-size: 1rem; }
        .sidebar-brand span { color: var(--sidebar-text); font-size: 0.75rem; }

        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .sidebar-section-title {
            color: rgba(255,255,255,0.3); font-size: 0.65rem;
            font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
            padding: 0.75rem 1.25rem 0.25rem;
        }

        .sidebar .nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            color: var(--sidebar-text); padding: 0.6rem 1.25rem;
            border-radius: 0; font-size: 0.875rem; transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            color: var(--sidebar-active);
            background: var(--sidebar-hover-bg);
        }
        .sidebar .nav-link.active {
            color: var(--sidebar-active);
            background: var(--sidebar-hover-bg);
            border-left-color: #4da3d4;
        }
        .sidebar .nav-link i { font-size: 1rem; width: 20px; text-align: center; }

        /* ─── MAIN CONTENT ────────────────────────────────────── */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }

        /* ─── TOPBAR ──────────────────────────────────────────── */
        .topbar {
            background: #fff; padding: 0.875rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 900;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .topbar-title { font-weight: 600; font-size: 1.05rem; color: #1a202c; }

        /* ─── CARDS ───────────────────────────────────────────── */
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .card-header { background: #fff; border-bottom: 1px solid #f0f4f8; border-radius: 12px 12px 0 0 !important; }

        /* ─── STAT CARDS ──────────────────────────────────────── */
        .stat-card {
            border-radius: 12px; padding: 1.25rem; color: #fff;
            display: flex; align-items: center; gap: 1rem;
        }
        .stat-card .stat-icon { font-size: 2rem; opacity: 0.85; }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: 0.8rem; opacity: 0.9; }

        /* ─── BADGES & STATUS ─────────────────────────────────── */
        .badge-status { padding: 0.35em 0.8em; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }

        /* ─── TABLES ──────────────────────────────────────────── */
        .table-hover tbody tr:hover { background: #f8fafb; }
        .table th { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: #718096; font-weight: 600; }

        /* ─── AVATAR ──────────────────────────────────────────── */
        .avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.85rem; }

        /* ─── RESPONSIVE ──────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="background:#4da3d4;border-radius:8px;padding:6px 10px;color:#fff;font-size:1.1rem;">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div>
                <h5>Cabinet Médical</h5>
                <span><?php echo e(ucfirst(auth()->user()->role_label)); ?></span>
            </div>
        </div>
    </div>

    <div class="sidebar-nav">
        
        <?php if(auth()->user()->isAdmin()): ?>
            <div class="sidebar-section-title">Principal</div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>
            <div class="sidebar-section-title">Gestion</div>
            <a href="<?php echo e(route('admin.users')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
                <i class="bi bi-people"></i> Utilisateurs
            </a>
            <a href="<?php echo e(route('patients.index')); ?>" class="nav-link <?php echo e(request()->routeIs('patients*') ? 'active' : ''); ?>">
                <i class="bi bi-person-heart"></i> Patients
            </a>
            <a href="<?php echo e(route('appointments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('appointments*') ? 'active' : ''); ?>">
                <i class="bi bi-calendar3"></i> Rendez-vous
            </a>
        <?php endif; ?>

        
        <?php if(auth()->user()->isDoctor()): ?>
            <div class="sidebar-section-title">Principal</div>
            <a href="<?php echo e(route('doctor.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('doctor.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>
            <div class="sidebar-section-title">Activité</div>
            <a href="<?php echo e(route('appointments.calendar')); ?>" class="nav-link <?php echo e(request()->routeIs('appointments.calendar') ? 'active' : ''); ?>">
                <i class="bi bi-calendar3"></i> Mon agenda
            </a>
            <a href="<?php echo e(route('appointments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('appointments.index') ? 'active' : ''); ?>">
                <i class="bi bi-list-check"></i> Mes rendez-vous
            </a>
            <a href="<?php echo e(route('patients.index')); ?>" class="nav-link <?php echo e(request()->routeIs('patients*') ? 'active' : ''); ?>">
                <i class="bi bi-person-heart"></i> Mes patients
            </a>
        <?php endif; ?>

        
        <?php if(auth()->user()->isSecretary()): ?>
            <div class="sidebar-section-title">Principal</div>
            <a href="<?php echo e(route('secretary.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('secretary.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>
            <div class="sidebar-section-title">Gestion</div>
            <a href="<?php echo e(route('appointments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('appointments*') ? 'active' : ''); ?>">
                <i class="bi bi-calendar3"></i> Rendez-vous
            </a>
            <a href="<?php echo e(route('patients.index')); ?>" class="nav-link <?php echo e(request()->routeIs('patients*') ? 'active' : ''); ?>">
                <i class="bi bi-person-heart"></i> Patients
            </a>
        <?php endif; ?>

        
        <?php if(auth()->user()->isPatient()): ?>
            <div class="sidebar-section-title">Mon espace</div>
            <a href="<?php echo e(route('patient.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('patient.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2"></i> Mon tableau de bord
            </a>
            <a href="<?php echo e(route('appointments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('appointments*') ? 'active' : ''); ?>">
                <i class="bi bi-calendar3"></i> Mes rendez-vous
            </a>
            <a href="<?php echo e(route('appointments.create')); ?>" class="nav-link <?php echo e(request()->routeIs('appointments.create') ? 'active' : ''); ?>">
                <i class="bi bi-calendar-plus"></i> Prendre RDV
            </a>
        <?php endif; ?>

        
        <div style="padding: 1rem 1.25rem; margin-top: auto;">
            <div class="sidebar-section-title">Compte</div>
            <a href="#" class="nav-link">
                <i class="bi bi-person-circle"></i> Mon profil
            </a>
        </div>
    </div>
</nav>


<div class="main-content">
    
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></span>
        </div>
        <div class="d-flex align-items-center gap-3">
            
            <div class="dropdown">
                <button class="btn btn-sm btn-light position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem">
                            <?php echo e(auth()->user()->unreadNotifications->count()); ?>

                        </span>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="width:300px; max-height:400px; overflow-y:auto;">
                    <div class="dropdown-header fw-bold">Notifications</div>
                    <?php $__empty_1 = true; $__currentLoopData = auth()->user()->unreadNotifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a class="dropdown-item py-2" href="#">
                            <div class="fw-semibold" style="font-size:0.85rem"><?php echo e($notif->data['message'] ?? ''); ?></div>
                            <div class="text-muted" style="font-size:0.75rem"><?php echo e($notif->created_at->diffForHumans()); ?></div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="dropdown-item text-muted text-center py-3">
                            <i class="bi bi-bell-slash"></i> Aucune notification
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="dropdown">
                <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="avatar" style="background:var(--primary)">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <span class="d-none d-md-inline text-dark fw-semibold" style="font-size:0.85rem"><?php echo e(auth()->user()->name); ?></span>
                    <i class="bi bi-chevron-down text-muted" style="font-size:0.7rem"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="px-3 py-2 text-muted" style="font-size:0.8rem"><?php echo e(auth()->user()->email); ?></div>
                    <div class="dropdown-divider"></div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="p-4">
        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/fr.global.min.js"></script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/layouts/app.blade.php ENDPATH**/ ?>