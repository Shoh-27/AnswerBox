<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Offline AI Journal')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --accent-color: #0d6efd;
        }

        [data-theme="dark"] {--bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #e9ecef;
            --text-secondary: #adb5bd;
            --border-color: #495057;
            --accent-color: #4dabf7;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .card {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .navbar {
            background-color: var(--bg-secondary) !important;
            border-bottom: 1px solid var(--border-color);
        }

        .form-control, .form-select {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            border-color: var(--accent-color);
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .score-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }

        .score-high {
            background-color: #198754;
        }

        .score-medium {
            background-color: #ffc107;
        }

        .score-low {
            background-color: #dc3545;
        }

        .favorite-icon {
            cursor: pointer;
            transition: color 0.2s;
        }

        .favorite-icon:hover {
            color: #ffc107;
        }

        .favorite-icon.active {
            color: #ffc107;
        }

        .theme-toggle {
            cursor: pointer;
            font-size: 1.2rem;
        }

        .result-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        textarea {
            resize: vertical;
        }

        .stat-card {
            text-align: center;
            padding: 1.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
    </style>

@yield('styles')</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-brain"></i> Offline AI Journal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
{{--            <ul class="navbar-nav me-auto">--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">--}}
{{--                        <i class="fas fa-home"></i> Home--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}" href="{{ route('history.index') }}">--}}
{{--                        <i class="fas fa-history"></i> History--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ request()->routeIs('answer.create') ? 'active' : '' }}" href="{{ route('answer.create') }}">--}}
{{--                        <i class="fas fa-plus"></i> Add Q&A--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">--}}
{{--                        <i class="fas fa-cog"></i> Settings--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
            <div class="d-flex align-items-center">
                    <span class="theme-toggle me-3" onclick="toggleTheme()">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </span>
                <span class="badge bg-secondary">
                        <i class="fas fa-wifi-slash"></i> Offline Mode
                    </span>
            </div>
        </div>
    </div>
</nav>
<!-- Flash Messages -->
@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<!-- Main Content -->
<main class="py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="mt-5 py-4 border-top">
    <div class="container text-center text-muted">
        <p class="mb-0">
            <i class="fas fa-brain"></i> Offline AI Journal - Learning from your questions
        </p>
        <small>Powered by string similarity algorithms</small>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Theme toggle
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        const icon = document.getElementById('themeIcon');
        icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);

        const icon = document.getElementById('themeIcon');
        icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });

    // Favorite toggle
    function toggleFavorite(promptId) {
        fetch(`/prompt/${promptId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
            .then(() => location.reload())
            .catch(error => console.error('Error:', error));
    }
</script>

@yield('scripts')
</body>
</html>
