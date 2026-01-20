<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat - Survei Inklusi</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* --- Clean White Theme --- */
        :root { --primary: #000000; --bg-body: #f8fafc; --surface: #ffffff; --border: #e2e8f0; --radius: 16px; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--primary); padding-bottom: 60px; }
        
        .navbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 0; margin-bottom: 30px; }
        .brand-text { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: #000; text-decoration: none; font-size: 1.1rem; }
        
        .container-detail { max-width: 850px; margin: 0 auto; padding: 0 20px; }
        
        .info-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        
        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-draft { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; }
        .status-submitted { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .status-accepted { background: #f0fdf4; color: #15803d; border: 1px solid #86efac; }

        .feedback-box { background-color: #fff1f2; border-left: 4px solid #e11d48; padding: 20px; border-radius: 8px; margin-top: 20px; }

        .accordion-item { border: 1px solid var(--border); margin-bottom: 15px; border-radius: 12px !important; overflow: hidden; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .accordion-header { margin-bottom: 0; }
        .accordion-button { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; color: #000; background: #fff; box-shadow: none !important; padding: 20px 25px; font-size: 1rem; }
        .accordion-button:not(.collapsed) { background-color: #f8fafc; color: #000; border-bottom: 1px solid var(--border); }
        .accordion-body { padding: 30px; background: #fff; }

        .q-item { margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px dashed #e2e8f0; }
        .q-item:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        .q-label { font-weight: 700; font-size: 0.95rem; margin-bottom: 12px; display: block; color: #0f172a; line-height: 1.5; }
        
        .q-answer { background: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; color: #334155; font-size: 0.95rem; display: inline-block; width: 100%; }
        
        .btn-back { color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; margin-bottom: 20px; font-size: 0.9rem; transition: 0.2s; }
        .btn-back:hover { color: #000; transform: translateX(-3px); }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="container container-detail">
            <a href="{{ route('dashboard.index') }}" class="brand-text">
                <i class="bi bi-grid-fill me-2"></i> IdeaHub Dashboard
            </a>
        </div>
    </nav>

    <div class="container container-detail">
        
        <a href="{{ route('dashboard.index') }}" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
        </a>

        <div class="info-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Detail Pengisian Survei</h4>
                    <div class="text-secondary small mt-1">
                        <i class="bi bi-calendar3 me-1"></i> 
                        Dikirim pada: <strong>{{ \Carbon\Carbon::parse($submission->submitted_at)->translatedFormat('d F Y, H:i') }} WIB</strong>
                    </div>
                </div>
                @if($submission->status == 'draft')
                    <span class="status-badge status-draft"><i class="bi bi-pencil-fill"></i> Draft</span>
                @elseif($submission->status == 'submitted')
                    <span class="status-badge status-submitted"><i class="bi bi-hourglass-split"></i> Menunggu Review</span>
                @elseif($submission->status == 'rejected')
                    <span class="status-badge status-rejected"><i class="bi bi-exclamation-circle-fill"></i> Perlu Revisi</span>
                @elseif($submission->status == 'accepted')
                    <span class="status-badge status-accepted"><i class="bi bi-check-circle-fill"></i> Diterima</span>
                @endif
            </div>

            @if($submission->status == 'rejected' && $submission->admin_feedback)
                <div class="feedback-box">
                    <h6 class="fw-bold text-danger mb-2">
                        <i class="bi bi-chat-square-quote-fill me-2"></i>Catatan Admin:
                    </h6>
                    <p class="text-dark mb-0 small fst-italic">"{{ $submission->admin_feedback }}"</p>
                    <div class="mt-3">
                        <a href="{{ route('survey.index') }}" class="btn btn-sm btn-dark rounded-pill px-3">Perbaiki Data <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            @endif
        </div>

        <div class="accordion" id="surveyAccordion">
            
            @foreach($chunks as $dimensionName => $chunkQuestions)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}">
                            <span class="badge bg-dark rounded-pill me-3" style="font-size: 0.75rem; padding: 6px 10px;">{{ $loop->iteration }}</span>
                            {{ $dimensionName ?? 'Bagian ' . $loop->iteration }}
                        </button>
                    </h2>
                    <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                         data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            
                            @foreach($chunkQuestions as $q)
                                @if(str_contains($q->question, 'Q0.')) @continue @endif

                                @php
                                    $val = $answers[$q->id] ?? null;
                                    
                                    // Decode JSON jika perlu
                                    $isJson = false;
                                    if ($val && !is_numeric($val) && is_string($val)) {
                                        $decoded = json_decode($val, true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $val = $decoded;
                                            $isJson = true;
                                        }
                                    }
                                @endphp

                                <div class="q-item">
                                    <label class="q-label">{{ $q->question }}</label>
                                    
                                    {{-- TIPE FILE --}}
                                    @if($q->type == 'file')
                                        <div class="q-answer bg-white border-0 p-0">
                                            @if($val && is_string($val) && $val != '-')
                                                <div class="d-flex align-items-center text-dark p-3 border rounded bg-light">
                                                    <i class="bi bi-file-earmark-pdf fs-3 text-danger me-3"></i>
                                                    <div>
                                                        <div class="small fw-bold text-break">{{ $val }}</div>
                                                        <div class="small text-secondary" style="font-size: 0.75rem;">File Terlampir</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic small">Tidak ada file</span>
                                            @endif
                                        </div>

                                    {{-- TIPE MATRIX (Q6a): LOOPING OPTIONS DARI DB (AGAR LABEL SESUAI) --}}
                                    @elseif($q->type == 'matrix')
                                        <div class="q-answer bg-white p-0 overflow-hidden border">
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0 table-borderless">
                                                    <thead class="bg-light border-bottom">
                                                        <tr>
                                                            <th class="ps-3 py-2 text-secondary fw-bold small">Kategori / Tipe</th>
                                                            <th class="pe-3 py-2 text-end text-secondary fw-bold small" style="width: 140px;">Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($q->options as $index => $opt)
                                                            @php
                                                                $answerValue = '-';
                                                                // Cek apakah ada jawaban tersimpan untuk label ini
                                                                if(is_array($val) && isset($val[$opt->option_label])) {
                                                                    $answerValue = $val[$opt->option_label];
                                                                } 
                                                                // Fallback: jika tersimpan sebagai index array (0,1,2)
                                                                elseif(is_array($val) && isset($val[$index]) && !is_string(array_key_first($val))) {
                                                                    $answerValue = $val[$index];
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td class="ps-3 py-2 align-middle text-dark">{{ $opt->option_label }}</td>
                                                                <td class="pe-3 py-2 text-end fw-bold text-dark align-middle">{{ $answerValue }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    {{-- TIPE CHECKBOX (LIST) --}}
                                    @elseif($q->type == 'checkbox' && is_array($val))
                                        <div class="q-answer bg-white p-0 overflow-hidden border">
                                            <div class="p-3">
                                                <div class="small text-secondary fw-bold text-uppercase mb-2">Pilihan Terpilih:</div>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($val as $v)
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-check2-square text-primary me-2 fs-5" style="line-height: 1;"></i>
                                                            <span class="text-dark">
                                                                {{ is_string($v) ? $v : json_encode($v) }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                    {{-- TIPE BIASA (TEXT/RADIO/NUMBER) --}}
                                    @else
                                        <div class="q-answer">
                                            {{ is_array($val) ? json_encode($val) : ($val ?? '-') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        
        <div class="text-center mt-5 text-secondary small opacity-50">
            &copy; {{ date('Y') }} IdeaHub Platform.
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>