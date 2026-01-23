@extends('admin.layouts.app', ['hideSidebar' => true])

@section('title', 'Review Submisi')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark rounded-circle p-2" style="width: 40px; height: 40px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">{{ $submission->user->university_name }}</h4>
            <span class="text-secondary small">{{ $submission->user->email }}</span>
        </div>
    </div>
    
    <div>
        @if($submission->status == 'accepted')
            <span class="badge bg-black text-white px-3 py-2 rounded-1 fs-6">Accepted</span>
        @elseif($submission->status == 'rejected')
            <span class="badge bg-danger text-white px-3 py-2 rounded-1 fs-6">Rejected</span>
        @else
            <span class="badge bg-warning text-dark px-3 py-2 rounded-1 fs-6">Pending Review</span>
        @endif
    </div>
</div>

<div class="card border-black rounded-4 shadow-sm mb-5">
        <div class="card-body p-0">
            <div class="accordion accordion-flush" id="adminViewAccordion">
                @foreach($chunks as $dimensionName => $chunkQuestions)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#dim{{ $loop->index }}">
                                {{ $dimensionName }}
                            </button>
                        </h2>
                        <div id="dim{{ $loop->index }}" class="accordion-collapse collapse">
                            <div class="accordion-body p-4">
                                @foreach($chunkQuestions as $q)
                                    @php 
                                        $val = $answers[$q->id] ?? null;
                                        // Auto Decode JSON
                                        if(is_string($val) && (str_starts_with($val,'[') || str_starts_with($val,'{'))) {
                                            $decoded = json_decode($val, true);
                                            if(json_last_error() === JSON_ERROR_NONE) $val = $decoded;
                                        }
                                    @endphp

                                    <div class="mb-4 border-bottom pb-3">
                                        <label class="d-block fw-bold text-secondary mb-2">{{ $q->question }}</label>
                                        
                                        {{-- TIPE FILE --}}
                                        @if($q->type == 'file' && $val)
                                            <div class="p-3 border rounded bg-light">
                                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i> {{ is_string($val)?$val:'File' }}
                                                <a href="#" class="float-end small text-decoration-none">Unduh</a>
                                            </div>

                                        {{-- PERBAIKAN: TIPE MATRIX (Q6a) --}}
                                        @elseif($q->type == 'matrix')
                                            <div class="table-responsive border rounded">
                                                <table class="table table-sm table-striped mb-0">
                                                    <thead class="bg-light">
                                                        <tr><th class="ps-3">Kategori</th><th class="text-end pe-3">Isian User</th></tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($q->options as $opt)
                                                            @php
                                                                $answerValue = '-';
                                                                // Cari jawaban dengan mencocokkan key secara teliti (trim spasi)
                                                                if (is_array($val)) {
                                                                    $dbLabel = trim($opt->option_label);
                                                                    foreach ($val as $key => $v) {
                                                                        if (trim($key) === $dbLabel) {
                                                                            $answerValue = $v;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td class="ps-3 text-secondary">{{ $opt->option_label }}</td>
                                                                <td class="text-end pe-3 fw-bold text-dark">{{ $answerValue }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        {{-- TIPE LAIN --}}
                                        @elseif(is_array($val)) 
                                            <ul class="mb-0 ps-3">@foreach($val as $v)<li>{{ is_string($v)?$v:json_encode($v) }}</li>@endforeach</ul>
                                        @else 
                                            <div class="fw-medium text-dark">{{ $val ?? '-' }}</div> 
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

<div class="fixed-bottom bg-white border-top border-secondary py-3 shadow-lg" style="z-index: 1050;">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <span class="text-secondary small fw-bold text-uppercase ls-1">Aksi Admin:</span>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger px-4 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-circle me-1"></i> Tolak / Revisi
            </button>
            
            <form id="acceptForm" action="{{ route('admin.update', $submission->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="accepted">
                <button type="button" class="btn btn-success px-4 fw-bold text-white rounded-3" onclick="confirmAccept()">
                    <i class="bi bi-check-circle me-1"></i> Terima Submisi
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="rejectForm" action="{{ route('admin.update', $submission->id) }}" method="POST" class="modal-content rounded-4 border-0 shadow">
            @csrf
            <input type="hidden" name="status" value="rejected">
            
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Tolak & Minta Revisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase text-secondary">Catatan Revisi</label>
                    <textarea name="admin_feedback" class="form-control bg-light" rows="5" required placeholder="Jelaskan bagian mana yang perlu diperbaiki oleh user..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger fw-bold px-4">Kirim Revisi</button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT KHUSUS HALAMAN INI --}}
<script>
    function confirmAccept() {
        Swal.fire({
            title: 'Terima Submisi?',
            text: "Data akan ditandai valid. User tidak bisa mengedit lagi.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Terima!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('acceptForm').submit();
            }
        })
    }

    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault(); 
        
        Swal.fire({
            title: 'Kirim Revisi?',
            text: "User akan mendapatkan notifikasi untuk memperbaiki data.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim Revisi',
            cancelButtonText: 'Cek Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    });
</script>

@endsection