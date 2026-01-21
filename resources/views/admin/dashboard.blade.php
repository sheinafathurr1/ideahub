@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
    
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold mb-1 brand-font">Dashboard Overview</h2>
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
                <div class="stat-icon border border-black rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['total_users'] }}</h3>
                <span class="text-secondary small fw-bold text-uppercase">Total User</span>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon border border-black rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; border-style: dashed !important;">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                <span class="text-secondary small fw-bold text-uppercase">Menunggu</span>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-black text-white rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-check-lg fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['accepted'] }}</h3>
                <span class="text-secondary small fw-bold text-uppercase">Diterima</span>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon border border-black rounded-3 d-flex align-items-center justify-content-center mb-3 text-black" style="width: 48px; height: 48px;">
                    <i class="bi bi-x-lg fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['rejected'] }}</h3>
                <span class="text-secondary small fw-bold text-uppercase">Revisi</span>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill text-warning"></i> Perlu Tindakan
                <span class="badge bg-black rounded-pill ms-1">{{ $stats['pending'] }}</span>
            </h5>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
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
                                    <div class="fw-bold text-dark">{{ $sub->user->university_name ?? 'Tanpa Nama' }}</div>
                                    <div class="small text-secondary">{{ $sub->user->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y') }}</div>
                                    <div class="small text-secondary">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('H:i') }} WIB</div>
                                </td>
                                <td><span class="badge bg-light text-dark border border-secondary">{{ $sub->user->university_type ?? '-' }}</span></td>
                                <td>
                                    <span class="badge bg-white text-dark border border-dark border-dashed">
                                        <i class="bi bi-clock me-1"></i> Review
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.show', $sub->id) }}" class="btn btn-dark btn-sm px-3 rounded-2 fw-bold">
                                        Review <i class="bi bi-arrow-right ms-1"></i>
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
        <div class="table-card">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
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
                                    <div class="fw-bold text-dark">{{ $sub->user->university_name }}</div>
                                </td>
                                <td class="text-secondary fw-medium">{{ $sub->updated_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($sub->status == 'accepted')
                                        <span class="badge bg-black text-white px-3 py-2 rounded-1">
                                            <i class="bi bi-check-lg me-1"></i> Accepted
                                        </span>
                                    @else
                                        <span class="badge bg-white text-black border border-black px-3 py-2 rounded-1 text-decoration-line-through">
                                            <i class="bi bi-x-lg me-1"></i> Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.show', $sub->id) }}" class="btn btn-outline-dark btn-sm px-3 rounded-2">
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

@endsection