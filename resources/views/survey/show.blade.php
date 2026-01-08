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
        :root { --primary: #000000; --bg-body: #ffffff; --surface: #ffffff; --border: #e2e2e2; --radius: 16px; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--primary); padding-bottom: 60px; }
        
        /* Navbar */
        .navbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 0; margin-bottom: 30px; }
        .brand-text { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: #000; text-decoration: none; font-size: 1.1rem; }
        
        .container-detail { max-width: 800px; margin: 0 auto; padding: 0 20px; }
        
        /* Header Info Card */
        .info-card { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; padding: 25px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .badge-status { background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; border: 1px solid #a7f3d0; }

        /* Accordion Custom Style */
        .accordion-item { border: 1px solid var(--border); margin-bottom: 15px; border-radius: 12px !important; overflow: hidden; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .accordion-header { margin-bottom: 0; }
        .accordion-button { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; color: #000; background: #fff; box-shadow: none !important; padding: 20px 25px; font-size: 1rem; }
        .accordion-button:not(.collapsed) { background-color: #fbfbfb; color: #000; border-bottom: 1px solid var(--border); }
        .accordion-body { padding: 30px; background: #fff; }

        /* Question Item */
        .q-item { margin-bottom: 30px; }
        .q-item:last-child { margin-bottom: 0; }
        .q-label { font-weight: 600; font-size: 0.95rem; margin-bottom: 10px; display: block; color: #1e293b; line-height: 1.5; }
        
        /* Answer Box */
        .q-answer { 
            background: #f8fafc; 
            padding: 14px 18px; 
            border-radius: 8px; 
            border: 1px solid #e2e8f0; 
            color: #334155; 
            font-size: 0.95rem;
            display: inline-block;
            width: 100%;
        }
        
        /* Table untuk Matrix/Checkbox */
        .answer-table { width: 100%; font-size: 0.9rem; }
        .answer-table td { padding: 8px 0; border-bottom: 1px dashed #e2e8f0; }
        .answer-table tr:last-child td { border-bottom: none; }
        .key-col { color: #64748b; width: 70%; }
        .val-col { font-weight: 600; color: #000; text-align: right; }

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
            <div>
                <h4 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Detail Pengisian Survei</h4>
                <div class="text-secondary small mt-2">
                    <i class="bi bi-calendar3 me-2"></i> 
                    Dikirim pada: <strong>{{ \Carbon\Carbon::parse($submission->submitted_at)->translatedFormat('d F Y, H:i') }} WIB</strong>
                </div>
            </div>
            <span class="badge-status">
                <i class="bi bi-check-circle-fill me-1"></i> Selesai
            </span>
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
                                    
                                    // Deteksi JSON (Untuk Matrix / Checkbox Multi)
                                    $isJson = false;
                                    if ($val && !is_numeric($val) && is_string($val)) {
                                        json_decode($val);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $val = json_decode($val, true);
                                            $isJson = true;
                                        }
                                    }
                                @endphp

                                <div class="q-item">
                                    <label class="q-label">{{ $q->question }}</label>
                                    
                                    @if($q->type == 'file')
                                        <div class="q-answer bg-white">
                                            @if($val && $val != '-')
                                                <div class="d-flex align-items-center text-dark">
                                                    <div class="bg-light p-2 rounded me-3 border">
                                                        <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="small fw-bold">{{ $val }}</div>
                                                        <div class="small text-secondary">File Terlampir</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Tidak ada file diunggah</span>
                                            @endif
                                        </div>

                                    @elseif($isJson && is_array($val))
                                        <div class="q-answer bg-white p-0 overflow-hidden">
                                            <div class="p-3 bg-light border-bottom small text-secondary fw-bold text-uppercase">Rincian Jawaban</div>
                                            <div class="p-3">
                                                <table class="answer-table">
                                                    @foreach($val as $k => $v)
                                                        <tr>
                                                            @if(is_numeric($k)) 
                                                                <td class="key-col text-dark"><i class="bi bi-check-square-fill text-primary me-2"></i> {{ $v }}</td>
                                                                <td class="val-col"></td>
                                                            @else
                                                                <td class="key-col">{{ $k }}</td>
                                                                <td class="val-col">{{ $v }}</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>

                                    @else
                                        <div class="q-answer">
                                            {{ $val ?? '-' }}
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