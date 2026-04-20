<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentification') | Cabinet Médical</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #1a6b9a; }
        body {
            background: linear-gradient(135deg, #1a1f36 0%, #1a6b9a 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-card {
            background: #fff; border-radius: 20px; padding: 2.5rem;
            width: 100%; max-width: 440px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .auth-logo {
            width: 60px; height: 60px; background: var(--primary);
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: #fff; margin: 0 auto 1.25rem;
        }
        .auth-card h2 { font-size: 1.5rem; font-weight: 700; color: #1a202c; }
        .auth-card .subtitle { color: #718096; font-size: 0.875rem; }
        .form-control {
            border-radius: 10px; padding: 0.7rem 1rem; border-color: #e2e8f0;
            font-size: 0.875rem;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,107,154,0.15); }
        .btn-primary {
            background: var(--primary); border: none; border-radius: 10px;
            padding: 0.75rem; font-weight: 600; letter-spacing: 0.02em;
        }
        .btn-primary:hover { background: #145580; }
        .demo-creds { background: #f0f7ff; border-radius: 10px; padding: 1rem; }
        .demo-creds code { font-size: 0.78rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="auth-logo">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <h2>Cabinet Médical</h2>
            <p class="subtitle">@yield('subtitle', 'Accès sécurisé au système de gestion')</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
