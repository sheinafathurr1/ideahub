<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #000000;
            --bg-body: #ffffff;
            --sidebar-width: 260px;
            --border-color: #000000;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--primary);
        }

        h1, h2, h3, h4, h5, h6 {
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

        /* --- TABLE STYLE --- */
        .table-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #000; /* Border Hitam */
            overflow: hidden;
            box-shadow: 4px 4px 0px rgba(0,0,0,1); /* Shadow Brutalist */
        }

        .table th {
            background: #f4f4f4;
            color: #000;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px 24px;
            font-weight: 700;
            border-bottom: 1px solid #000;
        }

        .table td {
            padding: 16px 24px;
            vertical-align: middle;
            color: #000;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .table tbody tr:hover {
            background-color: #fafafa;
        }

        /* --- BUTTONS & BADGES --- */
        .btn-black {
            background: #000;
            color: white;
            border: 1px solid #000;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 50px;
            transition: 0.2s;
        }

        .btn-black:hover {
            background: white;
            color: black;
        }

        .badge-mono {
            background: #f4f4f4;
            color: #000;
            border: 1px solid #e5e5e5;
            padding: 4px 10px;
            font-weight: 600;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        /* Pagination Style Override */
        .pagination .page-item .page-link {
            color: #000;
            border: 1px solid #e5e5e5;
        }
        .pagination .page-item.active .page-link {
            background-color: #000;
            border-color: #000;
            color: #fff;
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
        <a href="{{ route('admin.users') }}" class="nav-link active">
            <i class="bi bi-people-fill"></i> Users
        </a>
        {{-- <a href="{{ route('admin.reports') }}" class="nav-link">
            <i class="bi bi-file-earmark-bar-graph-fill"></i> Reports
        </a> --}}
        <a href="{{ route('admin.settings') }}" class="nav-link">
            <i class="bi bi-gear-fill"></i> Settings
        </a>
    </div>

    <button onclick="confirmLogout()" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Keluar
    </button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</nav>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold mb-0">Daftar Pengguna</h2>
        
        <button class="btn-black d-flex align-items-center gap-2">
            <i class="bi bi-download"></i> Export CSV
        </button>
    </div>

    <div class="table-card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Nama / Email</th>
                    <th>Institusi</th>
                    <th>Tanggal Bergabung</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-black">{{ $user->name }}</div>
                            <div class="small text-secondary">{{ $user->email }}</div>
                        </td>
                        <td>
                            <div class="fw-medium text-black">{{ $user->university_name ?? '-' }}</div>
                            <span class="badge-mono">{{ $user->university_type ?? 'Umum' }}</span>
                        </td>
                        <td class="text-secondary small fw-medium">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-link text-dark p-0 border-0"><i class="bi bi-three-dots-vertical fs-5"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-5 text-secondary">Belum ada user terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
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