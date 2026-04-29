@extends('admin.layouts.app')

@section('title', 'Pusat Laporan')

@section('content')
    
    <div class="mb-5">
        <h1 class="fw-bold mb-2 brand-font">Pusat Laporan</h1>
        <p class="text-secondary mb-0">Ringkasan statistik dan ekspor data survei IdeaHub.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <i class="bi bi-folder-fill fs-1 position-absolute end-0 bottom-0 text-light opacity-25 me-3 mb-3"></i>
                <span class="fw-bold text-secondary small text-uppercase ls-1">Total Submisi</span>
                <div class="display-5 fw-bold my-2">{{ $reportData['total'] }}</div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-dark" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <i class="bi bi-check-circle-fill fs-1 position-absolute end-0 bottom-0 text-light opacity-25 me-3 mb-3"></i>
                <span class="fw-bold text-dark small text-uppercase ls-1">Disetujui</span>
                <div class="display-5 fw-bold my-2">{{ $reportData['accepted'] }}</div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-secondary" style="width: {{ $reportData['total'] > 0 ? ($reportData['accepted']/$reportData['total'])*100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <i class="bi bi-x-circle-fill fs-1 position-absolute end-0 bottom-0 text-light opacity-25 me-3 mb-3"></i>
                <span class="fw-bold text-dark small text-uppercase ls-1">Perlu Revisi</span>
                <div class="display-5 fw-bold my-2">{{ $reportData['rejected'] }}</div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-danger bg-opacity-75" style="width: {{ $reportData['total'] > 0 ? ($reportData['rejected']/$reportData['total'])*100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <i class="bi bi-hourglass-top fs-1 position-absolute end-0 bottom-0 text-light opacity-25 me-3 mb-3"></i>
                <span class="fw-bold text-dark small text-uppercase ls-1">Menunggu</span>
                <div class="display-5 fw-bold my-2">{{ $reportData['submitted'] }}</div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning bg-opacity-75" style="width: {{ $reportData['total'] > 0 ? ($reportData['submitted']/$reportData['total'])*100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-lg-6">
            <div class="card border-black rounded-4 p-4 p-lg-5 shadow-sm h-100">
                <h3 class="fw-bold mb-3">Export Data</h3>
                <p class="text-secondary mb-4">Unduh data hasil survei dalam format CSV (Pivot). Data file akan dijadikan link aktif.</p>
                
                <div class="bg-light p-4 rounded-4 border mt-auto">
                    <form action="{{ route('admin.reports.export') }}" method="GET">
                        <label class="form-label fw-bold small text-uppercase mb-2 text-secondary">Filter Data</label>
                        <div class="mb-3">
                            <select name="status" class="form-select border-secondary py-3 fw-medium">
                                <option value="">Semua Data (All Data)</option>
                                <option value="accepted">Hanya yang Disetujui (Accepted)</option>
                                <option value="rejected">Hanya yang Ditolak (Rejected)</option>
                                <option value="submitted">Hanya yang Menunggu (Pending)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 d-flex align-items-center justify-content-center gap-2">
                            Unduh .CSV <i class="bi bi-download"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-black rounded-4 p-4 p-lg-5 shadow-sm h-100">
                <h3 class="fw-bold mb-3">Import Data</h3>
                <p class="text-secondary mb-4">Unggah kembali file CSV hasil export. Jika email user sudah ada, datanya akan diperbarui secara otomatis.</p>
                
                <div class="bg-light p-4 rounded-4 border border-dashed mt-auto">
                    <form action="{{ route('admin.reports.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="form-label fw-bold small text-uppercase mb-2 text-secondary">Unggah File CSV</label>
                        <div class="mb-3">
                            <input type="file" name="csv_file" class="form-control border-secondary py-2" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-outline-dark border-2 w-100 py-3 fw-bold rounded-3 d-flex align-items-center justify-content-center gap-2">
                            Mulai Import <i class="bi bi-upload"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection