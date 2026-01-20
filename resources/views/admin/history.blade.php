<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Submisi - Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --primary: #000000; --bg-body: #ffffff; --sidebar-width: 260px; --border-color: #e5e5e5; }
        body { background-color: var(--bg-body); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--primary); }
        
        /* Sidebar */
        .sidebar { width: var(--sidebar-width); background: var(--primary); min-height: 100vh; position: fixed; top: 0; left: 0; padding: 24px; display: flex; flex-direction: column; }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; width: calc(100% - var(--sidebar-width)); }
        
        .brand-logo { color: white; text-decoration: none; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; margin-bottom: 40px; }
        .nav-link { color: #888; padding: 12px 16px; border-radius: 6px; display: flex; gap: 12px; margin-bottom: 4px; text-decoration: none; transition: 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.15); }
        
        /* Table Styles */
        .table-card { border: 1px solid var(--primary); border-radius: 8px; overflow: hidden; }
        .table th { background: #f8fafc; color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 16px 24px; border-bottom: 1px solid var(--border-color); }
        .table td { padding: 16px 24px; vertical-align: middle; border-bottom: 1px solid var(--border-color); }
        
        /* Form Elements */
        .search-input { border: 1px solid var(--primary); border-radius: 6px 0 0 6px; padding: 10px 15px; }
        .search-btn { background: var(--primary); color: white; border: 1px solid var(--primary); border-radius: 0 6px 6px 0; padding: 0 20px; }
        .search-btn:hover { background: #333; }
        .filter-select { border: 1px solid var(--primary); border-radius: 6px; padding: 10px; width: 150px; cursor: pointer; }

        /* Badges */
        .badge-custom { padding: 6px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: inline-flex; align-items: center; gap: 5px; }
        .badge-accepted { background: var(--primary); color: white; border: 1px solid var(--primary); }
        .badge-rejected { background: white; color: var(--primary); border: 1px solid var(--primary); text-decoration: line-through; }

        /* Pagination Custom */
        .pagination .page-item .page-link { color: var(--primary); border-color: #dee2e6; }
        .pagination .page-item.active .page-link { background-color: var(--primary); border-color: var(--primary); color: white; }
    </style>
</head>
<body>

<nav class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand-logo">
        <div style="width: 32px; height: 32px; background: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: black;"><i class="bi bi-grid-fill"></i></div>
        IdeaHub Admin
    </a>
    <div class="d-flex flex-column gap-1">
        <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="{{ route('admin.users') }}" class="nav-link"><i class="bi bi-people-fill"></i> Users</a>
        <a href="{{ route('admin.history') }}" class="nav-link active"><i class="bi bi-clock-history"></i> History</a>
        <a href="{{ route('admin.reports') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill"></i> Reports</a>
        <a href="{{ route('admin.settings') }}" class="nav-link"><i class="bi bi-gear-fill"></i> Settings</a>
    </div>
</nav>

<main class="main-content">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Submisi</h2>
            <p class="text-secondary mb-0">Arsip data survei yang telah selesai diproses.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('admin.history') }}" method="GET" class="d-flex gap-3">
                
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <div class="input-group">
                    <input type="text" name="search" class="form-control search-input" 
                           placeholder="Cari nama kampus, email, atau user..." 
                           value="{{ request('search') }}">
                    <button class="btn search-btn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                @if(request('status') || request('search'))
                    <a href="{{ route('admin.history') }}" class="btn btn-outline-dark d-flex align-items-center">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="table-card">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4">Identitas Kampus</th>
                    <th>User / Kontak</th>
                    <th>Tgl Proses</th>
                    <th>Status Akhir</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $sub->user->university_name ?? '-' }}</div>
                            <div class="small text-secondary">{{ $sub->user->university_type ?? 'Umum' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $sub->user->name }}</div>
                            <div class="small text-secondary">{{ $sub->user->email }}</div>
                        </td>
                        <td>
                            <div class="text-dark">{{ $sub->updated_at->format('d M Y') }}</div>
                            <div class="small text-secondary">{{ $sub->updated_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            @if($sub->status == 'accepted')
                                <span class="badge-custom badge-accepted">
                                    <i class="bi bi-check-circle-fill"></i> Accepted
                                </span>
                            @else
                                <span class="badge-custom badge-rejected">
                                    <i class="bi bi-x-circle-fill"></i> Rejected
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.show', $sub->id) }}" class="btn btn-sm btn-outline-dark rounded-2 px-3">
                                Detail <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                            Tidak ada riwayat submisi ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $submissions->links() }}
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>