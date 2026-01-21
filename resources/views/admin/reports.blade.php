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

    <div class="card border-black rounded-4 p-4 p-lg-5 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h3 class="fw-bold mb-3">Export Data Jawaban</h3>
                <p class="text-secondary mb-4" style="max-width: 500px; line-height: 1.6;">
                    Unduh data lengkap hasil survei dalam format CSV yang kompatibel dengan Microsoft Excel. Data akan disusun secara horizontal (Pivot) berdasarkan pertanyaan.
                </p>
                
                <div class="d-flex gap-4 text-secondary small fw-medium">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-filetype-csv fs-5 text-dark"></i> Format .CSV
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-layout-three-columns fs-5 text-dark"></i> Struktur Pivot
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-all fs-5 text-dark"></i> UTF-8 Support
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-light p-4 rounded-4 border">
                    <form action="{{ route('admin.reports.export') }}" method="GET">
                        <label class="form-label fw-bold small text-uppercase mb-2 text-secondary">Filter Data</label>
                        
                        <div class="mb-3">
                            <select name="status" class="form-select border-secondary w-100 py-3 fw-medium">
                                <option value="">Semua Data (All Data)</option>
                                <option value="accepted">Hanya yang Disetujui (Accepted)</option>
                                <option value="rejected">Hanya yang Ditolak (Rejected)</option>
                                <option value="submitted">Hanya yang Menunggu (Pending)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 d-flex align-items-center justify-content-center gap-2">
                            Unduh Data Sekarang <i class="bi bi-download"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection