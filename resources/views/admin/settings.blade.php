<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    
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
        }

        h1, h2, h3, h4, h5, h6, .brand-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* --- SIDEBAR (Sama dengan Dashboard) --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 24px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #000;
        }

        .brand-logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            text-decoration: none;
        }

        .nav-link {
            color: #a3a3a3;
            padding: 12px 16px;
            border-radius: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
            text-decoration: none;
            transition: 0.2s;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            background: white;
            color: black;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255,255,255,0.1);
        }

        .logout-btn {
            margin-top: auto;
            color: white;
            background: transparent;
            border: 1px solid #333;
            padding: 12px;
            border-radius: 6px;
            width: 100%;
            text-align: left;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logout-btn:hover {
            background: white;
            color: black;
            border-color: white;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            width: calc(100% - var(--sidebar-width));
        }
        
        /* --- SETTINGS CARD --- */
        .settings-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #000; /* Border Hitam Tegas */
            padding: 40px;
            max-width: 700px;
            box-shadow: 6px 6px 0px rgba(0,0,0,1); /* Shadow Brutalist Hitam */
        }

        .form-control {
            border: 1px solid #ccc;
            padding: 12px;
            border-radius: 6px;
            color: #000;
        }

        .form-control:focus {
            border-color: #000;
            box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
        }

        .form-control:disabled {
            background-color: #f4f4f4;
            color: #666;
        }

        .btn-save {
            background: #000;
            color: white;
            border: 1px solid #000;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 6px;
            transition: 0.2s;
        }

        .btn-save:hover {
            background: white;
            color: black;
            transform: translateY(-2px);
            box-shadow: 2px 2px 0px rgba(0,0,0,0.2);
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; padding: 20px; }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand-logo">
        <div style="width: 36px; height: 36px; background: white; color: black; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-grid-fill" style="font-size: 1rem;"></i>
        </div>
        IdeaHub Admin
    </a>
    <div class="d-flex flex-column gap-1">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link">
            <i class="bi bi-people-fill"></i> Users
        </a>
        {{-- <a href="{{ route('admin.reports') }}" class="nav-link">
            <i class="bi bi-file-earmark-bar-graph-fill"></i> Reports
        </a> --}}
        <a href="{{ route('admin.settings') }}" class="nav-link active">
            <i class="bi bi-gear-fill"></i> Settings
        </a>
    </div>

    <button onclick="confirmLogout()" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Keluar
    </button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</nav>

<main class="main-content">
    <h2 class="fw-bold mb-5">Pengaturan Akun</h2>

    <div class="settings-card">
        <form action="{{ route('admin.settings') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <h5 class="fw-bold text-black border-bottom border-black pb-3 mb-4">Profil Admin</h5>
                
                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Alamat Email</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                    <div class="form-text mt-2"><i class="bi bi-lock-fill me-1"></i> Email tidak dapat diubah demi keamanan.</div>
                </div>
            </div>

            <div class="mb-5">
                <h5 class="fw-bold text-black border-bottom border-black pb-3 mb-4">Keamanan</h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end pt-3">
                <button type="submit" class="btn-save">
                    Simpan Perubahan <i class="bi bi-check-lg ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfigurasi SweetAlert Tema Hitam Putih
    const bwSwal = Swal.mixin({
        confirmButtonColor: '#000000',
        cancelButtonColor: '#ffffff',
        cancelButtonText: '<span style="color:black">Batal</span>',
        buttonsStyling: true,
        customClass: {
            confirmButton: 'btn btn-dark px-4 py-2 rounded-1',
            cancelButton: 'btn btn-light border border-dark px-4 py-2 rounded-1 ms-2'
        }
    });

    @if(session('success'))
        bwSwal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500,
            iconColor: '#000'
        });
    @endif
    
    @if($errors->any())
        bwSwal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ $errors->first() }}",
            iconColor: '#000'
        });
    @endif

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

</body>
</html>