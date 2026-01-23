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
            --black: #000000;
            --white: #ffffff;
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
            color: var(--black);
            background: var(--white);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }

        /* NAVBAR */
        .navbar-landing {
            background: var(--black);
            padding: 1rem 0;
            border-bottom: none;
        }

        .navbar-landing .navbar-brand {
            color: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-landing .nav-link {
            color: var(--white);
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        .navbar-landing .nav-link:hover {
            font-weight: 600;
        }

        .navbar-landing .btn-login {
            background: var(--black);
            color: var(--white);
            /* border: 2px solid var(--white); */
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s;
            border-radius: 0;
        }

        .navbar-landing .btn-login:hover {
            background: var(--white);
            color: var(--black);
        }

        /* FOOTER */
        .footer-landing {
            background: var(--black);
            color: var(--white);
            padding: 3rem 0 2rem;
            /* margin-top: 5rem; */
        }

        .footer-landing h5 {
            font-size: 1rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .footer-landing a {
            color: var(--white);
            text-decoration: none;
            display: block;
            padding: 0.25rem 0;
            transition: all 0.2s;
        }

        .footer-landing a:hover {
            font-weight: 600;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
        }

        /* UTILITY */
        .section-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            color: var(--gray-700);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .btn-primary-custom {
            background: var(--black);
            color: var(--white);
            border: 2px solid var(--black);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background: var(--white);
            color: var(--black);
        }

        .btn-outline-custom {
            background: var(--white);
            color: var(--black);
            border: 2px solid var(--black);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-custom:hover {
            background: var(--black);
            color: var(--white);
        }

        @media (max-width: 768px) {
            .navbar-landing .navbar-collapse {
                background: var(--black);
                padding: 1rem;
                margin-top: 1rem;
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
                <div style="width: 40px; height: 40px; background: var(--white); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-grid-fill" style="color: var(--black);"></i>
                </div>
                IdeaHub
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.universities') }}">Browse Universities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.news') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-login" href="{{ route('login') }}">Login</a>
                    </li>
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
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>IdeaHub</h5>
                    <p style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">
                        Platform pendataan universitas inklusif untuk mahasiswa disabilitas di Indonesia.
                    </p>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <a href="{{ route('landing.universities') }}">Browse Universities</a>
                    <a href="{{ route('landing.news') }}">Berita & Insight</a>
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Legal</h5>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
            
            <div class="footer-bottom">
                &copy; {{ date('Y') }} IdeaHub. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>