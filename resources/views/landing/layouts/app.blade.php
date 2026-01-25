<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IdeaHub') - Platform Inklusi Disabilitas</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --maroon-primary: #BA1D2F;
            --red-accent: #D3273E;
            --orange-primary: #F2C75C;
            --gray-light: #D0D3D4;
            --white: #FFFFFF;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-700: #495057;
            --gray-900: #212529;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--orange-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }

        /* SMOOTH SCROLLING */
        html {
            scroll-behavior: smooth;
        }

        /* NAVBAR */
        .navbar-landing {
            background: var(--maroon-primary);
            padding: 1.5rem 0;
            border-bottom: none;
            transition: padding 0.3s ease;
        }

        .navbar-landing .navbar-brand {
            color: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            filter: grayscale(100%) brightness(0) invert(1);
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .navbar-brand-logo {
            width: 7rem;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        .navbar-landing .nav-link {
            color: var(--white);
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
        }

        .navbar-landing .nav-link::after {
            display: none;
        }

        .navbar-landing .btn-login {
            background: var(--orange-primary);
            color: var(--maroon-primary);
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .navbar-landing .btn-login:hover {
            background: var(--white);
            color: var(--maroon-primary);
            transform: translateY(-2px);
        }

        /* FOOTER */
        .footer-landing {
            background: var(--maroon-primary);
            color: var(--white);
            padding: 0 0 2rem;
        }

        .footer-landing h5 {
            font-size: 1rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .footer-landing a {
            color: var(--black);
            text-decoration: none;
            display: block;
            padding: 0.25rem 0;
            transition: all 0.2s ease;
            position: relative;
            width: fit-content;
        }

        .footer-landing a::after {
            display: none;
        }

        .footer-bottom {
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-bottom a {
            display: inline;
        }

        /* UTILITY */
        .section-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .section-subtitle {
            color: var(--gray-700);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .btn-primary-custom {
            background: var(--orange-primary);
            color: var(--maroon-primary);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background: var(--white);
            color: var(--maroon-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-outline-custom {
            background: var(--white);
            color: var(--maroon-primary);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-custom:hover {
            background: var(--orange-primary);
            color: var(--maroon-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .navbar-landing {
                padding: 1rem 0;
            }

            .navbar-brand-logo {
                width: 5rem;
            }

            .navbar-landing .navbar-collapse {
                background: var(--black);
                padding: 1rem;
                margin-top: 1rem;
                border-top: 1px solid rgba(255,255,255,0.1);
            }

            .navbar-landing .nav-link::after {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 1.75rem;
            }

            .section-subtitle {
                font-size: 1rem;
            }

            .btn-primary-custom,
            .btn-outline-custom {
                padding: 0.65rem 1.5rem;
                font-size: 0.95rem;
            }

            .footer-landing a {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand-logo {
                width: 4rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .section-subtitle {
                font-size: 0.95rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-landing navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing.home') }}">
                @if(file_exists(public_path('images/ideahub-logo.png')))
                    <img src="{{ asset('images/ideahub-logo.png') }}" alt="IdeaHub" class="navbar-brand-logo">
                @else
                    <div style="width: 40px; height: 40px; background: var(--white); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-grid-fill" style="color: var(--white);"></i>
                    </div>
                @endif
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.universities') }}">Browse Universities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.news') }}">News and Insight</a>
                    </li>
                    <!-- <li class="nav-item mt-3 mt-lg-0">
                        <a class="btn btn-login" href="{{ route('login') }}">Login</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer-landing">
        <div class="container">
            <div class="row" style="padding: 3rem 0;">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="d-flex align-items-center gap-3">
                        @if(file_exists(public_path('images/ideahub-logo.png')))
                            <img src="{{ asset('images/ideahub-logo.png') }}" alt="IdeaHub" style="width: 8rem; object-fit: contain; filter: grayscale(100%) brightness(0) invert(1);">
                        @else
                            <div style="width: 60px; height: 60px; background: var(--white); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-grid-fill" style="color: var(--white); font-size: 2rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4 text-md-start">
                            <a href="{{ route('landing.universities') }}">Browse Universities</a>
                            <a href="{{ route('landing.news') }}">News and Insight</a>
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-4 col-md-4 text-center text-md-start mb-2 mb-md-0">
                        &copy; {{ date('Y') }} IdeaHub. All rights reserved.
                    </div>
                    <div class="col-4 col-md-4 text-center mb-2 mb-md-0">
                        <a href="#">Privacy Policy</a>
                    </div>
                    <div class="col-4 col-md-4 text-center mb-2 mb-md-0">
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>