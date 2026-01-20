<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IdeaHub</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            /* --- MONOCHROME PALETTE --- */
            --primary: #000000;
            --secondary: #666666;
            --bg-body: #ffffff;
            --bg-surface: #ffffff;
            --border-color: #000000;
            --sidebar-width: 260px;
            --radius: 0px; /* Opsional: Radius 0 bikin lebih kesan tegas/brutalist */
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--primary);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .brand-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--primary);
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            padding: 24px;
            border-right: 1px solid var(--border-color);
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
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: #a3a3a3;
            padding: 12px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
            border: 1px solid transparent;
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

        /* --- STAT CARDS (MONOCHROME) --- */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e5e5e5;
            transition: transform 0.2s, border-color 0.2s;
            display: flex;
            align-items: center;
            gap: 20px;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: black;
            box-shadow: 4px 4px 0px rgba(0,0,0,1); /* Shadow tegas hitam */
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid #000;
            background: white;
            color: black;
        }
        
        /* Variasi Icon Hitam Penuh untuk highlight */
        .stat-icon.filled {
            background: black;
            color: white;
        }

        /* --- TABLE --- */
        .table-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #000; /* Border hitam tegas */
            overflow: hidden;
        }

        .table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #000;
            font-weight: 700;
            background: #f4f4f4;
            padding: 16px 24px;
            border-bottom: 1px solid #000;
        }

        .table td {
            padding: 16px 24px;
            vertical-align: middle;
            color: #000;
            font-size: 0.9rem;
            border-bottom: 1px solid #e5e5e5;
        }

        .table tbody tr:hover {
            background-color: #fafafa;
        }

        /* --- BADGES (Custom B&W) --- */
        .badge-custom {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Pending: Putih, Border Putus-putus */
        .badge-pending { 
            background: #fff; 
            color: #555; 
            border: 1px dashed #555; 
        }
        
        /* Accepted: Hitam Penuh */
        .badge-accepted { 
            background: #000; 
            color: #fff; 
            border: 1px solid #000; 
        }
        
        /* Rejected: Putih, Border Tebal */
        .badge-rejected { 
            background: #fff; 
            color: #000; 
            border: 1px solid #000; 
            
        }

        .badge-type { 
            background: #f4f4f4; 
            color: #000; 
            border: 1px solid #e5e5e5; 
            padding: 4px 10px; 
            font-weight: 600; 
            border-radius: 4px; 
        }

        /* --- BUTTONS --- */
        .btn-review {
            background: #000;
            color: white;
            border: 1px solid #000;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-review:hover {
            background: white;
            color: black;
        }

        .btn-detail { 
            color: #000; 
            font-weight: 600; 
            font-size: 0.85rem; 
            text-decoration: none; 
            padding: 6px 12px; 
            border-radius: 6px; 
            transition: 0.2s; 
            background: transparent; 
            border: 1px solid #e5e5e5; 
        }
        
        .btn-detail:hover { 
            border-color: #000;
            background: #f4f4f4;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); width: 260px; }
            .main-content { margin-left: 0; width: 100%; padding: 20px; }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <a href="#" class="brand-logo">
        <div style="width: 36px; height: 36px; background: white; color: black; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-grid-fill" style="font-size: 1rem;"></i>
        </div>
        IdeaHub Admin
    </a>

    <div class="d-flex flex-column gap-1">
        <a href="{{ route('admin.dashboard') }}" class="nav-link active">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link">
            <i class="bi bi-people-fill"></i> Users
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-link">
            <i class="bi bi-file-earmark-bar-graph-fill"></i> Reports
        </a>
        <a href="{{ route('admin.settings') }}" class="nav-link">
            <i class="bi bi-gear-fill"></i> Settings
        </a>
        <a href="{{ route('admin.history') }}" class="nav-link {{ Route::is('admin.history') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> History
        </a>
    </div>

    <button onclick="confirmLogout()" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Keluar
    </button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</nav>

<main class="main-content">
    
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Overview</h2>
            <p class="text-secondary mb-0">Selamat datang kembali, Administrator.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white px-3 py-2 border d-flex align-items-center gap-2 rounded-2">
                <div class="bg-black rounded-circle" style="width: 8px; height: 8px;"></div>
                <span class="small fw-bold">{{ date('d M Y') }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_users'] }}</h3>
                    <span class="text-secondary small fw-bold text-uppercase">Total User</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="border-style: dashed;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                    <span class="text-secondary small fw-bold text-uppercase">Menunggu</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon filled">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['accepted'] }}</h3>
                    <span class="text-secondary small fw-bold text-uppercase">Diterima</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="border-color: #000; color: #000;">
                    <i class="bi bi-x-lg"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['rejected'] }}</h3>
                    <span class="text-secondary small fw-bold text-uppercase">Revisi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill"></i> Perlu Tindakan
                <span class="badge bg-black rounded-pill ms-1">{{ $stats['pending'] }}</span>
            </h5>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Identitas Kampus</th>
                            <th>Tanggal Submit</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingSubmissions as $sub)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $sub->user->university_name ?? 'Tanpa Nama' }}</div>
                                    <div class="small text-secondary">{{ $sub->user->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y') }}</div>
                                    <div class="small text-secondary">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('H:i') }} WIB</div>
                                </td>
                                <td><span class="badge-type">{{ $sub->user->university_type ?? '-' }}</span></td>
                                <td>
                                    <span class="badge-custom badge-pending">
                                        <i class="bi bi-clock me-1"></i> Review
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.show', $sub->id) }}" class="btn-review">
                                        Review <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-secondary opacity-50 mb-2"><i class="bi bi-clipboard-check fs-1"></i></div>
                                    <div class="fw-medium text-secondary">Tidak ada submisi baru yang perlu direview.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <h5 class="fw-bold mb-3">Riwayat Aktivitas Terakhir</h5>
        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Identitas Kampus</th>
                            <th>Tanggal Proses</th>
                            <th>Status Akhir</th>
                            <th class="text-end pe-4">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historySubmissions as $sub)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $sub->user->university_name }}</div>
                                </td>
                                <td class="text-secondary fw-medium">{{ $sub->updated_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($sub->status == 'accepted')
                                        <span class="badge-custom badge-accepted"><i class="bi bi-check me-1"></i> Accepted</span>
                                    @else
                                        <span class="badge-custom badge-rejected"><i class="bi bi-x me-1"></i> Rejected</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.show', $sub->id) }}" class="btn-detail">
                                        Lihat Data
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Konfigurasi SweetAlert dengan Tema Hitam Putih
    const bwSwal = Swal.mixin({
        confirmButtonColor: '#000000', // Tombol konfirmasi Hitam
        cancelButtonColor: '#ffffff',  // Tombol batal Putih
        cancelButtonText: '<span style="color:black">Batal</span>', // Teks batal hitam
        buttonsStyling: true,
        customClass: {
            confirmButton: 'btn btn-dark px-4 py-2 rounded-1', // Pakai class bootstrap dark
            cancelButton: 'btn btn-light border border-dark px-4 py-2 rounded-1 ms-2'
        }
    });

    // 1. Notifikasi Flash Message (Success)
    @if(session('success'))
        bwSwal.fire({
            icon: 'success', // Icon tetap berwarna standar agar user aware
            title: 'Berhasil',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    @endif

    // 2. Konfirmasi Logout
    function confirmLogout() {
        bwSwal.fire({
            title: 'Keluar?',
            text: "Anda akan mengakhiri sesi admin ini.",
            icon: 'warning',
            iconColor: '#000', // Icon warning jadi hitam
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