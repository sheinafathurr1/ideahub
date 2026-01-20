<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Laporan - IdeaHub Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #000000;
            --secondary: #64748b;
            --bg-body: #fafafa; /* Sedikit abu-abu sangat muda agar kartu pop-out */
            --surface: #ffffff;
            --border: #e2e8f0;
            --sidebar-width: 260px;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--primary);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            padding: 30px 24px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .brand-logo {
            color: white;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 50px;
            letter-spacing: -0.5px;
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: white;
            color: black;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }

        .nav-link {
            color: #94a3b8;
            padding: 14px 16px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex; align-items: center; gap: 12px;
            text-decoration: none;
            margin-bottom: 6px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: white;
            color: black;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 50px;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 1.75rem;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: var(--secondary);
            font-size: 0.95rem;
        }

        /* --- STAT CARDS --- */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.06);
            border-color: #cbd5e1;
        }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .stat-icon-bg {
            position: absolute;
            right: -20px;
            bottom: -20px;
            font-size: 8rem;
            color: #f1f5f9;
            opacity: 0.5;
            transform: rotate(-15deg);
            z-index: 0;
            pointer-events: none;
        }

        .progress-container {
            position: relative;
            z-index: 1;
        }

        .progress {
            height: 6px;
            background-color: #f1f5f9;
            border-radius: 10px;
            overflow: hidden;
        }

        /* --- DOWNLOAD SECTION --- */
        .download-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            margin-top: 50px;
            position: relative;
            overflow: hidden;
        }

        .download-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 6px; height: 100%;
            background: black;
        }

        .form-select-custom {
            padding: 14px 20px;
            border-radius: 10px;
            border: 1px solid var(--border);
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--primary);
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s;
        }

        .form-select-custom:focus {
            border-color: black;
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
            background-color: white;
        }

        .btn-download {
            background: black;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-download:hover {
            background: #333;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        /* Custom Colors for Bars (Monochrome Variant) */
        .bar-dark { background-color: #0f172a; }
        .bar-success { background-color: #334155; } /* Dark Grey */
        .bar-danger { background-color: #94a3b8; } /* Light Grey */
        .bar-warning { background-color: #cbd5e1; } /* Lighter Grey */

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 24px; }
        }
    </style>
</head>
<body>

    <nav class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand-logo">
            <div class="brand-icon"><i class="bi bi-grid-fill"></i></div>
            <div>IdeaHub<span style="font-weight: 400; opacity: 0.7;">Admin</span></div>
        </a>
        
        <div class="d-flex flex-column gap-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link">
                <i class="bi bi-people"></i> Pengguna
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-link active">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
            </a>
            <a href="{{ route('admin.settings') }}" class="nav-link">
                <i class="bi bi-gear"></i> Pengaturan
            </a>
        </div>
    </nav>

    <main class="main-content">
        
        <div class="page-header">
            <h1 class="page-title">Pusat Laporan</h1>
            <p class="page-subtitle">Ringkasan statistik dan ekspor data survei IdeaHub.</p>
        </div>

        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <i class="bi bi-folder-fill stat-icon-bg"></i>
                    <span class="stat-label">Total Submisi</span>
                    <div class="stat-value">{{ $reportData['total'] }}</div>
                    <div class="progress-container">
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Partisipasi</span>
                            <span>100%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bar-dark" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <i class="bi bi-check-circle-fill stat-icon-bg"></i>
                    <span class="stat-label text-dark">Disetujui (Accepted)</span>
                    <div class="stat-value">{{ $reportData['accepted'] }}</div>
                    <div class="progress-container">
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Rasio</span>
                            <span>{{ $reportData['total'] > 0 ? round(($reportData['accepted']/$reportData['total'])*100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bar-success" style="width: {{ $reportData['total'] > 0 ? ($reportData['accepted']/$reportData['total'])*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <i class="bi bi-x-circle-fill stat-icon-bg"></i>
                    <span class="stat-label text-dark">Perlu Revisi (Rejected)</span>
                    <div class="stat-value">{{ $reportData['rejected'] }}</div>
                    <div class="progress-container">
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Rasio</span>
                            <span>{{ $reportData['total'] > 0 ? round(($reportData['rejected']/$reportData['total'])*100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bar-danger" style="width: {{ $reportData['total'] > 0 ? ($reportData['rejected']/$reportData['total'])*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <i class="bi bi-hourglass-top stat-icon-bg"></i>
                    <span class="stat-label text-dark">Menunggu (Pending)</span>
                    <div class="stat-value">{{ $reportData['submitted'] }}</div>
                    <div class="progress-container">
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Rasio</span>
                            <span>{{ $reportData['total'] > 0 ? round(($reportData['submitted']/$reportData['total'])*100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bar-warning" style="width: {{ $reportData['total'] > 0 ? ($reportData['submitted']/$reportData['total'])*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="download-card shadow-sm">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h3 class="fw-bold mb-2">Export Data Jawaban</h3>
                    <p class="text-secondary mb-4" style="max-width: 500px; line-height: 1.6;">
                        Unduh data lengkap hasil survei dalam format CSV yang kompatibel dengan Microsoft Excel. Data akan disusun secara horizontal (Pivot) berdasarkan pertanyaan.
                    </p>
                    
                    <div class="d-flex gap-3 text-secondary small">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-filetype-csv fs-5"></i> Format .CSV
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-layout-three-columns fs-5"></i> Struktur Pivot
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-all fs-5"></i> UTF-8 Support
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bg-white p-4 rounded-4 border">
                        <form action="{{ route('admin.reports.export') }}" method="GET">
                            <label class="form-label fw-bold small text-uppercase mb-2 text-secondary">Filter Data</label>
                            
                            <div class="mb-3">
                                <select name="status" class="form-select form-select-custom w-100">
                                    <option value="">Semua Data (All Data)</option>
                                    <option value="accepted">Hanya yang Disetujui (Accepted)</option>
                                    <option value="rejected">Hanya yang Ditolak (Rejected)</option>
                                    <option value="submitted">Hanya yang Menunggu (Pending)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn-download">
                                Unduh Data Sekarang <i class="bi bi-download"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>