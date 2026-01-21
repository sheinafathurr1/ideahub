<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - IdeaHub</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary: #000000;
            --bg-body: #ffffff;
            --sidebar-width: 260px;
            --border-color: #e5e5e5;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--primary);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* --- LAYOUT STRUCTURE --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            padding: 24px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        /* --- SIDEBAR ELEMENTS --- */
        .brand-logo {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 40px;
        }

        .nav-link {
            color: #a3a3a3;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 4px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(2px);
        }

        .nav-link.active {
            background: white;
            color: black;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255,255,255,0.1);
        }

        .logout-btn {
            margin-top: auto;
            color: #ef4444; /* Merah untuk logout agar kontras */
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            text-align: left;
            font-weight: 600;
            display: flex; align-items: center; gap: 10px;
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background: #ef4444;
            color: white;
        }

        /* --- COMMON COMPONENTS --- */
        .stat-card {
            background: white;
            border: 1px solid var(--primary);
            border-radius: 12px;
            padding: 24px;
            height: 100%;
            transition: 0.2s;
        }
        .stat-card:hover {
            box-shadow: 6px 6px 0px var(--primary);
            transform: translateY(-2px);
        }

        .table-card {
            border: 1px solid var(--primary);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table th {
            background: #f8fafc;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px 24px;
        }
        
        .table td {
            padding: 16px 24px;
            vertical-align: middle;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; padding: 20px; }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #000; }
    </style>
    
    @stack('styles')
</head>
<body>

    @if(!($hideSidebar ?? false))
        @include('admin.layouts.sidebar')
    @endif

    <main class="main-content" style="{{ ($hideSidebar ?? false) ? 'margin-left: 0 !important; width: 100% !important; padding: 40px 10%;' : '' }}">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Konfigurasi Global SweetAlert Tema Hitam Putih
        const bwSwal = Swal.mixin({
            confirmButtonColor: '#000000',
            cancelButtonColor: '#ffffff',
            cancelButtonText: '<span style="color:black">Batal</span>',
            buttonsStyling: true,
            customClass: {
                confirmButton: 'btn btn-dark px-4 py-2 rounded-2 fw-bold',
                cancelButton: 'btn btn-light border border-dark px-4 py-2 rounded-2 ms-2 fw-bold'
            }
        });

        // Auto Flash Message (Success)
        @if(session('success'))
            bwSwal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                iconColor: '#000',
                toast: true,
                position: 'top-end'
            });
        @endif
        
        // Auto Flash Message (Error)
        @if($errors->any())
            bwSwal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ $errors->first() }}",
                iconColor: '#dc3545'
            });
        @endif

        // Global Logout Function
        function confirmLogout() {
            bwSwal.fire({
                title: 'Keluar?',
                text: "Anda akan mengakhiri sesi admin ini.",
                icon: 'warning',
                iconColor: '#000',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>

    @stack('scripts')
</body>
</html>