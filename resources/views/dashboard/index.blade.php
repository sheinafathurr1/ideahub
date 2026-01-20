<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Survei Inklusi</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root { --primary: #000000; --bg-body: #f8fafc; --surface: #ffffff; --border: #e2e8f0; --radius: 16px; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--primary); min-height: 100vh; }
        
        .navbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 0; }
        .brand-text { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: #000; text-decoration: none; font-size: 1.1rem; }
        .card-box { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 30px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        
        .btn-black { background: #000; color: #fff; padding: 12px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.2s; border: none; }
        .btn-black:hover { background: #333; color: #fff; transform: translateY(-2px); }
        
        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-new { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .status-draft { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; }
        .status-submitted { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .status-accepted { background: #f0fdf4; color: #15803d; border: 1px solid #86efac; }

        .feedback-box { background-color: #fff1f2; border-left: 4px solid #e11d48; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px; }
        
        .action-icon { width: 60px; height: 60px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #000; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .progress { height: 8px; border-radius: 10px; background-color: #f1f5f9; margin-top: 0; }
        .progress-bar { background-color: #000; border-radius: 10px; }
    </style>
</head>
<body>

    <nav class="navbar mb-5">
        <div class="container" style="max-width: 1000px;">
            <a href="#" class="brand-text"><i class="bi bi-grid-fill me-2"></i> IdeaHub Dashboard</a>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-md-block small text-secondary">Halo, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-2">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 1000px;">
        
        @if(session('success'))
            <div class="alert alert-success border-0 bg-success-subtle text-success mb-4 rounded-3">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            
            <div class="col-lg-7">
                <div class="card-box h-100 d-flex flex-column">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="action-icon"><i class="bi bi-clipboard-data"></i></div>
                        
                        @if($uiState == 'draft')
                            <span class="status-badge status-draft"><i class="bi bi-pencil-fill"></i> Draft</span>
                        @elseif($uiState == 'submitted')
                            <span class="status-badge status-submitted"><i class="bi bi-hourglass-split"></i> Menunggu Review</span>
                        @elseif($uiState == 'rejected')
                            <span class="status-badge status-rejected"><i class="bi bi-exclamation-circle-fill"></i> Perlu Revisi</span>
                        @else
                            <span class="status-badge status-new"><i class="bi bi-star-fill"></i> Siap Diisi</span>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-2">Survei Lanskap Inklusi</h4>
                    
                    <div class="text-secondary small mb-4" style="line-height: 1.6;">
                        @if($uiState == 'draft')
                            Anda memiliki pengisian yang belum selesai. Mohon selesaikan draft ini terlebih dahulu.
                        @elseif($uiState == 'submitted')
                            Data survei telah dikirim. Tim kami sedang melakukan verifikasi data Anda. Tombol aksi dikunci selama proses ini.
                        @elseif($uiState == 'rejected')
                            <span class="text-danger fw-bold">PENTING: Pengajuan Anda dikembalikan.</span> Silakan perbaiki data sesuai catatan admin dan kirim ulang.
                        @else
                            Silakan mulai pengisian data survei baru. Data yang sudah disetujui (Accepted) akan tersimpan di riwayat.
                        @endif
                    </div>

                    @if($uiState == 'rejected' && $feedback)
                        <div class="feedback-box">
                            <h6 class="fw-bold text-danger mb-1" style="font-size: 0.9rem;">
                                <i class="bi bi-chat-square-quote-fill me-2"></i>Catatan Admin:
                            </h6>
                            <p class="text-dark mb-0 small fst-italic">"{{ $feedback }}"</p>
                        </div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-light">
                        
                        @if($uiState == 'draft' || $uiState == 'rejected')
                            <div style="flex:1; margin-right: 15px;">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span class="text-secondary">Progress</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar {{ $uiState == 'rejected' ? 'bg-danger' : 'bg-black' }}" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                            <a href="{{ route('survey.index') }}" class="btn-black btn-sm">
                                {{ $uiState == 'rejected' ? 'Perbaiki Data' : 'Lanjutkan' }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>

                        @elseif($uiState == 'submitted')
                            <span class="text-secondary small">Dikirim: {{ \Carbon\Carbon::parse($pendingSubmission->submitted_at)->format('d M Y') }}</span>
                            <button class="btn btn-outline-secondary btn-sm" disabled>Sedang Direview</button>

                        @else
                            <span class="text-secondary small">Estimasi: 15-20 Menit</span>
                            <a href="{{ route('survey.index') }}" class="btn-black">Mulai Baru <i class="bi bi-plus-lg"></i></a>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                
                <div class="card-box bg-white mb-4">
                    <h6 class="fw-bold text-uppercase text-secondary small mb-4" style="letter-spacing: 1px;">Identitas Kampus</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="d-block text-secondary small" style="font-size: 0.75rem;">Nama Perguruan Tinggi</label>
                            <div class="fw-bold text-dark">{{ Auth::user()->university_name }}</div>
                        </div>
                        <div class="col-6">
                            <label class="d-block text-secondary small" style="font-size: 0.75rem;">Jenis PT</label>
                            <div class="fw-bold text-dark">{{ Auth::user()->university_type }}</div>
                        </div>
                        <div class="col-6">
                            <label class="d-block text-secondary small" style="font-size: 0.75rem;">Kategori</label>
                            <div class="fw-bold text-dark">{{ Auth::user()->university_category ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-box bg-white">
                    <h6 class="fw-bold text-uppercase text-secondary small mb-4" style="letter-spacing: 1px;">Riwayat Diterima</h6>
                    @if($completedSubmissions->count() > 0)
                        <div class="d-flex flex-column gap-2" style="max-height: 250px; overflow-y: auto;">
                            @foreach($completedSubmissions as $sub)
                                <a href="{{ route('survey.show', $sub->id) }}" 
                                   class="p-3 border rounded bg-white d-flex justify-content-between align-items-center text-decoration-none"
                                   style="transition: all 0.2s; border-left: 4px solid #15803d !important;">
                                    <div>
                                        <div class="fw-bold text-dark small">Survei #{{ $sub->id }}</div>
                                        <div class="text-success small" style="font-size: 0.75rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Accepted
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-secondary" style="font-size: 0.7rem;">
                                            {{ \Carbon\Carbon::parse($sub->submitted_at)->format('d/m/y') }}
                                        </div>
                                        <small class="text-dark">Detail <i class="bi bi-chevron-right"></i></small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 border rounded bg-light border-dashed">
                            <i class="bi bi-inbox text-secondary fs-3 d-block mb-2"></i>
                            <small class="text-secondary">Belum ada survei yang disetujui.</small>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const uiState = "{{ $uiState }}";
            // Ambil feedback dari PHP dan encode agar aman untuk JS
            const feedbackText = {!! json_encode($feedback) !!}; 
            
            // 1. NOTIFIKASI JIKA DITOLAK (REJECTED)
            // Tampil setiap kali user membuka dashboard selama statusnya masih 'rejected'
            if (uiState === 'rejected') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perlu Revisi',
                    html: `
                        <p class="text-secondary small mb-3">Admin telah meninjau submisi Anda dan meminta perbaikan data.</p>
                        <div class="bg-light p-3 rounded text-start border text-danger small fst-italic">
                            "${feedbackText ? feedbackText : 'Silakan perbaiki data sesuai ketentuan.'}"
                        </div>
                    `,
                    confirmButtonText: 'Perbaiki Sekarang',
                    confirmButtonColor: '#000000',
                    showCancelButton: true,
                    cancelButtonText: 'Nanti'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('survey.index') }}";
                    }
                });
            }

            // 2. NOTIFIKASI JIKA DITERIMA (ACCEPTED)
            // Logika: Cek apakah ada flash session 'success_accepted' atau cek status 'new' jika sebelumnya submitted
            // Cara termudah: Jika ada completed submission TERBARU (misal < 1 jam yang lalu), tampilkan.
            // Namun untuk simpelnya, kita cek jika ada parameter query ?status=accepted (opsional) atau gunakan Session Flash dari controller.
            
            // Kita gunakan Flash Message standar Laravel untuk notifikasi "Accepted"
            // (Anda bisa menambahkan with('status_accepted', true) di controller jika user login dan statusnya baru berubah)
        });
    </script>

</body>
</html>