<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Submisi - {{ $submission->user->university_name }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; padding-bottom: 80px; }
        .navbar { background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 0; }
        .main-container { max-width: 900px; margin: 0 auto; padding: 0 20px; }
        
        .action-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; border-top: 1px solid #e2e8f0;
            padding: 15px 0; z-index: 1000; box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
        }
        
        .accordion-item { border: 1px solid #e2e8f0; margin-bottom: 10px; border-radius: 8px !important; overflow: hidden; }
        .accordion-button:not(.collapsed) { background-color: #f1f5f9; color: black; }
        .q-label { font-weight: 700; font-size: 0.95rem; margin-bottom: 8px; display: block; }
        
        .table-matrix th { font-size: 0.85rem; color: #64748b; }
        .table-matrix td { padding: 8px 12px; }
    </style>
</head>
<body>

<nav class="navbar mb-4 sticky-top">
    <div class="container main-container d-flex justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-dark fw-bold">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
        </a>
        <span class="badge bg-dark">{{ $submission->user->university_name }}</span>
    </div>
</nav>

<div class="container main-container">
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Identitas Kampus</h5>
            <div class="row g-3 small">
                <div class="col-md-4">
                    <span class="text-secondary d-block">Nama Perguruan Tinggi</span>
                    <span class="fw-bold">{{ $submission->user->university_name }}</span>
                </div>
                <div class="col-md-4">
                    <span class="text-secondary d-block">Jenis PT</span>
                    <span class="fw-bold">{{ $submission->user->university_type }}</span>
                </div>
                <div class="col-md-4">
                    <span class="text-secondary d-block">Kontak</span>
                    <span class="fw-bold">{{ $submission->user->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion mb-5" id="adminViewAccordion">
        @foreach($chunks as $dimensionName => $chunkQuestions)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dim{{ $loop->index }}">
                        <span class="fw-bold">{{ $dimensionName }}</span>
                    </button>
                </h2>
                <div id="dim{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#adminViewAccordion">
                    <div class="accordion-body bg-white">
                        @foreach($chunkQuestions as $q)
                            @if(str_contains($q->question, 'Q0.')) @continue @endif
                            
                            @php
                                $val = $answers[$q->id] ?? '-';
                                $isJson = false;
                                if ($val && !is_numeric($val) && is_string($val)) {
                                    $decoded = json_decode($val, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        $val = $decoded;
                                        $isJson = true;
                                    }
                                }
                            @endphp

                            <div class="mb-4 pb-3 border-bottom">
                                <label class="q-label text-secondary">{{ $q->question }}</label>
                                
                                @if($q->type == 'file' && $val != '-')
                                    <div class="p-2 border rounded bg-light d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-pdf text-danger"></i> 
                                        <span class="fw-bold small">{{ is_string($val) ? $val : 'File' }}</span>
                                        <small class="text-muted ms-auto">(Klik untuk unduh)</small>
                                    </div>
                                @elseif($q->type == 'matrix' && is_array($val))
                                    <div class="bg-light p-2 rounded border">
                                        <table class="table table-sm table-borderless m-0 small">
                                            @foreach($val as $k => $v)
                                                <tr><td class="text-secondary">{{ $k }}</td><td class="fw-bold text-end">{{ $v }}</td></tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @elseif(is_array($val))
                                    <div class="bg-light p-2 rounded border">
                                        <ul class="mb-0 ps-3 small">
                                            @foreach($val as $v) <li>{{ is_string($v) ? $v : json_encode($v) }}</li> @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="fw-bold text-dark">{{ $val }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

<div class="action-bar">
    <div class="container main-container d-flex justify-content-between align-items-center">
        <div>
            <span class="text-secondary small me-2">Status Saat Ini:</span>
            @if($submission->status == 'submitted')
                <span class="badge bg-warning text-dark">Menunggu Review</span>
            @elseif($submission->status == 'accepted')
                <span class="badge bg-success">Diterima</span>
            @elseif($submission->status == 'rejected')
                <span class="badge bg-danger">Ditolak</span>
            @endif
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-circle me-1"></i> Tolak / Revisi
            </button>
            
            <form id="acceptForm" action="{{ route('admin.update', $submission->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="accepted">
                <button type="button" class="btn btn-success px-4 fw-bold text-white" onclick="confirmAccept()">
                    <i class="bi bi-check-circle me-1"></i> Terima Submisi
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="rejectForm" action="{{ route('admin.update', $submission->id) }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="status" value="rejected">
            
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">Tolak & Minta Revisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Alasan Penolakan / Catatan Revisi</label>
                    <textarea name="admin_feedback" class="form-control" rows="5" required placeholder="Jelaskan bagian mana yang perlu diperbaiki oleh user..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Kirim Revisi</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Konfirmasi TERIMA
    function confirmAccept() {
        Swal.fire({
            title: 'Terima Submisi?',
            text: "Data akan ditandai sebagai VALID dan status menjadi Diterima.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754', // Hijau Bootstrap
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Terima!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('acceptForm').submit();
            }
        })
    }

    // 2. Konfirmasi TOLAK (Opsional, saat klik Kirim di modal)
    // Kita tambahkan event listener ke form modal
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Tahan submit asli
        
        Swal.fire({
            title: 'Kirim Revisi?',
            text: "User akan mendapatkan notifikasi untuk memperbaiki data ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', // Merah Bootstrap
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim Revisi',
            cancelButtonText: 'Periksa Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form secara manual via JS setelah konfirmasi
                e.target.submit();
            }
        });
    });
</script>

</body>
</html>