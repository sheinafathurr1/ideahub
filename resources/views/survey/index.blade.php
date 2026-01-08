<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Survei Inklusi Disabilitas</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- STYLE "CLEAN WHITE" --- */
        :root {
            --primary: #000000;
            --secondary: #666666;
            --bg-body: #ffffff;
            --surface: #ffffff;
            --border: #e2e2e2;
            --radius-card: 16px;
            --radius-sm: 8px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--primary);
            padding: 40px 0;
            min-height: 100vh;
        }

        .main-container { max-width: 800px; margin: 0 auto; padding: 0 20px; }

        /* HEADER CARD (Sticky/Atas) */
        .header-card {
            background: var(--surface);
            border: 1px solid var(--primary); /* Border Hitam Tegas */
            border-radius: var(--radius-card);
            padding: 25px 30px;
            margin-bottom: 30px;
            display: none; /* Hidden awal */
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .progress-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .progress-label { font-weight: 600; font-size: 1rem; min-width: 80px; }
        .progress-track {
            flex-grow: 1; height: 10px; background-color: #f1f1f1;
            border-radius: 20px; margin: 0 15px; overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        .progress-fill {
            height: 100%; width: 0%; background-color: #d1d1d1;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .progress-fill.active { background: linear-gradient(90deg, #333 0%, #000 100%); }

        .dimension-box {
            background-color: #f8f9fa; border-radius: 6px; padding: 12px 20px;
            font-size: 0.95rem; display: flex; align-items: center;
            border-left: 5px solid var(--primary); /* Aksen garis kiri */
        }

        /* SURVEY CARD (Bawah) */
        .survey-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 40px;
            min-height: 400px;
        }

        .question-label { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600; font-size: 1.15rem; 
            margin-bottom: 16px; line-height: 1.5; color: #1a1a1a;
        }
        
        .form-control, .form-select {
            background-color: #fcfcfc; border: 1px solid #cccccc;
            padding: 12px 16px; border-radius: var(--radius-sm);
        }
        .form-control:focus {
            border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,0,0,0.05);
        }

        /* SELECTION CARD (Radio/Checkbox) */
        .selection-card {
            display: flex; align-items: center; padding: 14px 18px;
            border: 1px solid #e0e0e0; border-radius: var(--radius-sm);
            cursor: pointer; transition: var(--transition);
            background: #fff; margin-bottom: 0;
        }
        .selection-card:hover { border-color: #999; background-color: #fafafa; }
        .selection-card.selected {
            border-color: #000; background-color: #f4f4f5; font-weight: 500;
        }
        .form-check-input {
            width: 1.2em; height: 1.2em; margin-right: 14px;
            border: 2px solid #aaa; cursor: pointer;
        }
        .form-check-input:checked { background-color: #000; border-color: #000; }

        /* BUTTONS */
        .btn-black {
            background: #000; color: #fff;
            padding: 12px 24px; border-radius: 50px;
            font-weight: 600; border: none;
            transition: var(--transition);
            min-width: 140px;
        }
        .btn-black:hover { background: #333; transform: translateY(-2px); }
        .btn-black:disabled { background: #ccc; cursor: not-allowed; transform: none; }
        
        .btn-outline {
            background: transparent; color: #000;
            border: 1px solid #ccc;
            padding: 12px 24px; border-radius: 50px;
            font-weight: 600; min-width: 120px;
        }
        .btn-outline:hover { border-color: #000; background: #f9f9f9; }

        .btn-draft {
            background: transparent; color: #666;
            border: 1px dashed #ccc;
            padding: 12px 20px; border-radius: 50px;
            font-weight: 500; font-size: 0.9rem;
        }
        .btn-draft:hover {
            color: #000; border-color: #999; background: #fdfdfd;
        }

        /* UTILS & ANIMATION */
        .wizard-section, .wizard-step { display: none; }
        .wizard-step.active { display: block; animation: fadeInUp 0.5s ease; }
        .fade-out { opacity: 0; display: none; transition: opacity 0.4s ease; }
        .logic-hidden { display: none !important; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Responsive Navigation */
        @media (max-width: 576px) {
            .nav-buttons { flex-direction: column-reverse; gap: 15px; }
            .btn-black, .btn-outline, .btn-draft { width: 100%; }
        }
    </style>
</head>
<body>

<div class="container main-container">
    
    <div class="text-center mb-5" id="introHeader">
        <h2 class="fw-bold display-6">Survei Lanskap Inklusi</h2>
        <p class="text-secondary fs-5">IdeaHub x Telkom University</p>
    </div>

    <div id="stickyHeader" class="header-card">
        <div class="progress-row">
            <span class="progress-label">Progress</span>
            <div class="progress-track">
                <div class="progress-fill active" id="progressBar"></div>
            </div>
            <span class="fw-bold" id="progressText">0%</span>
        </div>
        <div class="dimension-box">
            <span class="text-secondary me-2">Dimensi:</span>
            <span id="dimensionTitle" class="text-dark fw-bold text-uppercase">Memuat...</span>
        </div>
    </div>

    <div class="survey-card">
        
        <form action="{{ route('survey.store') }}" method="POST" id="surveyForm" enctype="multipart/form-data">
            @csrf

            <div id="consent-section">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">Pernyataan Persetujuan</h4>
                </div>

                <div class="p-4 bg-light rounded-3 mb-4 border">
                    <p class="mb-0 text-secondary" style="line-height: 1.6; text-align: justify;">
                        "Dengan ini, saya menyatakan bahwa saya bersedia untuk berpartisipasi dalam survei ini. 
                        Saya memahami bahwa data yang saya berikan akan digunakan untuk tujuan penelitian terkait 
                        inklusi disabilitas di perguruan tinggi Indonesia. Saya juga menyatakan bahwa informasi 
                        yang saya berikan adalah benar dan jujur sesuai dengan pengetahuan dan pengalaman saya."
                    </p>
                </div>

                <label class="selection-card mb-4" id="consentLabel">
                    <input type="checkbox" class="form-check-input" id="consentCheck">
                    <span class="fw-medium">Ya, saya mengerti dan bersedia berpartisipasi.</span>
                </label>

                {{-- Logic Hidden Input Q0 (Jika ada) --}}
                @php
                    $q0 = $chunks->flatten()->firstWhere(fn($q) => str_contains($q->question, 'Q0.'));
                @endphp
                @if($q0) <input type="hidden" name="q_{{ $q0->id }}" id="q0_input" value=""> @endif

                <div class="d-grid mt-2">
                    <button type="button" class="btn btn-black py-3" id="btnStartSurvey" disabled>
                        Mulai Survei
                    </button>
                </div>
            </div>

            <div id="wizard-section" class="wizard-section">
                
                @foreach($chunks as $dimensionName => $chunkQuestions)
                    <div class="wizard-step" 
                         data-step="{{ $loop->iteration }}" 
                         data-dimension-name="{{ $dimensionName ?? 'Bagian ' . $loop->iteration }}">
                        
                        @foreach($chunkQuestions as $q)
                            @if(str_contains($q->question, 'Q0.')) @continue @endif

                            @php
                                $oldVal = isset($existingAnswers) ? ($existingAnswers[$q->id] ?? null) : null;
                            @endphp

                            <div class="mb-5 question-wrapper" 
                                 id="wrapper_q_{{ $q->id }}"
                                 data-qid="{{ $q->id }}"
                                 data-depends-on="{{ $q->depends_on_question_id }}"
                                 data-depends-value="{{ $q->depends_on_value }}">
                                
                                <label class="question-label d-block">
                                    {{ $q->question }}
                                    @if($q->is_required) <span class="text-danger">*</span> @endif
                                </label>

                                {{-- TEXT / TEXTAREA --}}
                                @if($q->type == 'text' || $q->type == 'textarea')
                                    <input type="text" class="form-control logic-trigger" 
                                           name="q_{{ $q->id }}" 
                                           value="{{ $oldVal }}"
                                           placeholder="Jawaban Anda..."
                                           {{ $q->is_required ? 'required' : '' }}>

                                {{-- NUMBER --}}
                                @elseif($q->type == 'number')
                                    <input type="number" class="form-control logic-trigger" 
                                           name="q_{{ $q->id }}" 
                                           value="{{ $oldVal }}"
                                           placeholder="0" min="0"
                                           {{ $q->is_required ? 'required' : '' }}>

                               {{-- TIPE RADIO --}}
                                @elseif($q->type == 'radio')
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($q->options as $opt)
                                            @php
                                                // 1. Cek apakah ini opsi yang terpilih?
                                                $isSelected = ($oldVal == $opt->value);
                                                $customTextVal = '';

                                                // 2. Logika Populate "Lainnya"
                                                // Jika opsi ini punya fitur text input (has_text_input=1)
                                                if ($opt->has_text_input) {
                                                    // Cek apakah oldVal cocok dengan salah satu opsi standar?
                                                    $isStandard = $q->options->pluck('value')->contains($oldVal);
                                                    
                                                    // Jika oldVal ada isinya, TAPI tidak cocok dengan opsi standar manapun
                                                    // Berarti oldVal adalah custom text yang diketik user sebelumnya.
                                                    if ($oldVal && !$isStandard) {
                                                        $isSelected = true; // Anggap opsi "Lainnya" terpilih
                                                        $customTextVal = $oldVal; // Isi text field dengan jawaban user
                                                    }
                                                }
                                            @endphp

                                            <div class="selection-wrapper">
                                                <label class="selection-card {{ $isSelected ? 'selected' : '' }}">
                                                    <input class="form-check-input logic-trigger other-trigger" type="radio" 
                                                        name="q_{{ $q->id }}" 
                                                        value="{{ $opt->value }}"
                                                        data-has-text="{{ $opt->has_text_input }}"
                                                        data-qid="{{ $q->id }}"
                                                        {{ $isSelected ? 'checked' : '' }}
                                                        {{ $q->is_required ? 'required' : '' }}>
                                                    <span>{{ $opt->option_label }}</span>
                                                </label>

                                                {{-- INPUT TEXT TAMBAHAN --}}
                                                @if($opt->has_text_input)
                                                    <div class="mt-2 ms-4 other-input-container" style="{{ $isSelected ? '' : 'display: none;' }}">
                                                        <input type="text" 
                                                            name="other_text_{{ $q->id }}" 
                                                            class="form-control form-control-sm bg-white" 
                                                            placeholder="Tuliskan jawaban Anda..."
                                                            value="{{ $customTextVal }}"
                                                            {{ $isSelected ? 'required' : '' }}>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                {{-- TIPE CHECKBOX --}}
                                @elseif($q->type == 'checkbox')
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($q->options as $opt)
                                            @php 
                                                // Cek apakah value ini ada di array jawaban (standard check)
                                                $isChecked = is_array($oldVal) && in_array($opt->value, $oldVal);
                                                $customTextVal = '';

                                                // Logika Populate Checkbox Lainnya
                                                // Jika opsi ini punya input text, tapi value aslinya TIDAK ada di array oldVal
                                                // Kita perlu cek apakah ada value "asing" di array oldVal yang mungkin adalah custom text?
                                                if ($opt->has_text_input && is_array($oldVal)) {
                                                    // Ambil semua value opsi standar untuk pertanyaan ini
                                                    $standardValues = $q->options->pluck('value')->toArray();
                                                    
                                                    // Cari value di oldVal yang TIDAK ada di standardValues
                                                    $diff = array_diff($oldVal, $standardValues);
                                                    
                                                    // Jika ada sisa (custom text) dan saat ini kita sedang loop di opsi "Lainnya"
                                                    // Maka asumsikan sisa itu milik opsi ini.
                                                    if (!empty($diff) && !$isChecked) {
                                                        $isChecked = true;
                                                        $customTextVal = reset($diff); // Ambil teks pertama yang beda
                                                    }
                                                    
                                                    // Jika isChecked true (karena value standar "Lainnya" tersimpan), tapi custom text belum ketemu
                                                    // (Kasus user pilih Lainnya tapi belum isi teks, atau logic reset)
                                                    if ($isChecked && in_array($opt->value, $oldVal)) {
                                                        // Reset custom text agar tidak double jika value aslinya "Lainnya"
                                                        // (Tergantung bagaimana Anda menyimpan di DB. Logic Controller di atas menyimpan TEXT-nya langsung)
                                                    }
                                                }
                                            @endphp
                                            
                                            <div class="selection-wrapper">
                                                <label class="selection-card {{ $isChecked ? 'selected' : '' }}">
                                                    <input class="form-check-input logic-trigger other-trigger" type="checkbox" 
                                                        name="q_{{ $q->id }}[]" 
                                                        value="{{ $opt->value }}"
                                                        data-has-text="{{ $opt->has_text_input }}"
                                                        data-qid="{{ $q->id }}"
                                                        {{ $isChecked ? 'checked' : '' }}>
                                                    <span>{{ $opt->option_label }}</span>
                                                </label>

                                                @if($opt->has_text_input)
                                                    <div class="mt-2 ms-4 other-input-container" style="{{ $isChecked ? '' : 'display: none;' }}">
                                                        <input type="text" 
                                                            name="other_text_{{ $q->id }}_{{ str_replace(' ', '_', $opt->value) }}" 
                                                            class="form-control form-control-sm bg-white" 
                                                            placeholder="Tuliskan rincian..."
                                                            value="{{ $customTextVal }}"
                                                            {{ $isChecked ? 'required' : '' }}>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                {{-- SELECT --}}
                                @elseif($q->type == 'select')
                                     <select class="form-select logic-trigger" name="q_{{ $q->id }}" {{ $q->is_required ? 'required' : '' }}>
                                        <option value="" disabled {{ $oldVal ? '' : 'selected' }}>Pilih Opsi...</option>
                                        @foreach($q->options as $opt)
                                            <option value="{{ $opt->value }}" {{ $oldVal == $opt->value ? 'selected' : '' }}>
                                                {{ $opt->option_label }}
                                            </option>
                                        @endforeach
                                     </select>
                                
                                {{-- FILE --}}
                                @elseif($q->type == 'file')
                                    <div class="p-4 bg-light border rounded text-center">
                                        <i class="bi bi-cloud-arrow-up fs-3 text-secondary"></i>
                                        <input type="file" class="form-control mt-3" name="q_{{ $q->id }}" {{ $q->is_required && !$oldVal ? 'required' : '' }}>
                                        @if($oldVal) 
                                            <div class="small text-success mt-2">File sudah terunggah. Upload ulang untuk mengganti.</div>
                                        @else
                                            <div class="small text-secondary mt-2">Format: PDF/JPG (Max 2MB)</div>
                                        @endif
                                    </div>

                                {{-- MATRIX --}}
                                @elseif($q->type == 'matrix')
                                    <div class="border rounded p-3 bg-light">
                                        @foreach($q->options as $opt)
                                            @php 
                                                $matrixVal = isset($oldVal[$opt->option_label]) ? $oldVal[$opt->option_label] : '';
                                            @endphp
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                                <small class="fw-bold text-secondary me-2">{{ $opt->option_label }}</small>
                                                <input type="number" class="form-control form-control-sm text-end" style="width: 100px;" 
                                                       name="q_{{ $q->id }}[{{ $opt->option_label }}]" 
                                                       value="{{ $matrixVal }}"
                                                       placeholder="0">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top nav-buttons">
                            
                            <div style="flex: 1;">
                                @if(!$loop->first)
                                    <button type="button" class="btn btn-outline" onclick="changeStep(-1)">Kembali</button>
                                @endif
                            </div>

                            <div class="text-center" style="flex: 1;">
                                <button type="button" class="btn btn-draft" onclick="saveDraft()">
                                    <i class="bi bi-floppy me-1"></i> Simpan Draft
                                </button>
                            </div>

                            <div class="text-end" style="flex: 1;">
                                @if(!$loop->last)
                                    <button type="button" class="btn btn-black btn-next" onclick="changeStep(1)">
                                        Lanjut
                                    </button>
                                @else
                                    <button type="button" class="btn btn-black" onclick="finishSurvey()">Kirim Survei</button>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </form>
    </div>
</div>

<script>
    // --- SETUP ---
    const totalSteps = {{ $chunks->count() }};
    // Menggunakan data dari controller, default ke 1 jika belum ada
    let currentStep = {{ isset($submission) && $submission->current_step ? $submission->current_step : 1 }}; 
    
    // --- DOM Elements ---
    const stickyHeader = document.getElementById('stickyHeader');
    const introHeader = document.getElementById('introHeader');
    const consentSection = document.getElementById('consent-section');
    const wizardSection = document.getElementById('wizard-section');
    const dimensionTitle = document.getElementById('dimensionTitle');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const q0Input = document.getElementById('q0_input');

    // --- INIT ---
    document.addEventListener("DOMContentLoaded", function() {
        // Jika user sudah pernah isi (Draft), lewati intro & consent, langsung ke step terakhir
        @if(isset($submission) && $submission->current_step > 0)
            introHeader.style.display = 'none';
            consentSection.style.display = 'none';
            stickyHeader.style.display = 'block';
            wizardSection.style.display = 'block';
            showStep(currentStep);
        @endif
        
        runLogicCheck(); 
    });

    // --- CONSENT LOGIC ---
    document.getElementById('consentCheck').addEventListener('change', function() {
        document.getElementById('btnStartSurvey').disabled = !this.checked;
        if(this.checked) {
            this.closest('.selection-card').classList.add('selected');
            if(q0Input) q0Input.value = "Ya";
        } else {
            this.closest('.selection-card').classList.remove('selected');
            if(q0Input) q0Input.value = "";
        }
    });

    document.getElementById('btnStartSurvey').addEventListener('click', function() {
        consentSection.classList.add('fade-out');
        introHeader.classList.add('fade-out');
        
        setTimeout(() => {
            consentSection.style.display = 'none';
            introHeader.style.display = 'none';
            
            stickyHeader.style.display = 'block';
            wizardSection.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            showStep(1);
        }, 300);
    });

    // --- LOGIC OTHER INPUT (LAINNYA) ---
    document.addEventListener('change', function(e) {
        // Cek jika elemen yang berubah memiliki class 'other-trigger'
        if (e.target.classList.contains('other-trigger')) {
            handleOtherInput(e.target);
        }
    });

    function handleOtherInput(input) {
        const hasText = input.getAttribute('data-has-text') == '1';
        
        // Hanya proses jika opsi ini punya fitur input text
        // atau jika ini radio button (karena radio perlu menutup input 'Lainnya' jika pindah pilihan)
        if (input.type === 'radio') {
            const container = input.closest('.d-flex.flex-column'); // Wrapper satu pertanyaan
            
            // 1. Reset semua input text di pertanyaan ini (Sembunyikan & Clear)
            container.querySelectorAll('.other-input-container').forEach(el => {
                el.style.display = 'none';
                const txt = el.querySelector('input');
                if(txt) {
                    txt.value = ''; 
                    txt.removeAttribute('required');
                }
            });

            // 2. Jika opsi yang BARU dipilih punya has_text, tampilkan
            if (hasText && input.checked) {
                const wrapper = input.closest('.selection-wrapper');
                const textContainer = wrapper.querySelector('.other-input-container');
                if(textContainer) {
                    textContainer.style.display = 'block';
                    const txt = textContainer.querySelector('input');
                    txt.setAttribute('required', 'required'); // Wajib diisi
                    txt.focus();
                }
            }
        } 
        else if (input.type === 'checkbox') {
            // Logic Checkbox lebih simpel (Toggle)
            if (hasText) {
                const textContainer = input.closest('.selection-wrapper').querySelector('.other-input-container');
                if(textContainer) {
                    if (input.checked) {
                        textContainer.style.display = 'block';
                        const txt = textContainer.querySelector('input');
                        txt.setAttribute('required', 'required');
                        txt.focus();
                    } else {
                        textContainer.style.display = 'none';
                        const txt = textContainer.querySelector('input');
                        txt.value = '';
                        txt.removeAttribute('required');
                    }
                }
            }
        }
    }

    // --- WIZARD LOGIC ---
    function showStep(step) {
        // Sembunyikan semua step
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
        
        // Tampilkan step aktif
        const activeStepEl = document.querySelector(`.wizard-step[data-step="${step}"]`);
        
        if(activeStepEl) {
            activeStepEl.classList.add('active');
            
            // Update Header Dimension Title
            const dimName = activeStepEl.getAttribute('data-dimension-name');
            dimensionTitle.innerText = dimName;

            // Update Progress Bar
            let percent = Math.round(((step - 1) / totalSteps) * 100);
            if(step === totalSteps) percent = 100;
            progressBar.style.width = percent + '%';
            progressText.innerText = percent + '%';

            // Update Indikator Halaman di Tombol "Lanjut"
            const nextBtn = activeStepEl.querySelector('.btn-next');
            if(nextBtn) {
                nextBtn.innerHTML = `Lanjut (${step}/${totalSteps}) <i class="bi bi-arrow-right ms-1"></i>`;
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function changeStep(n) {
        if (n === 1 && !validateCurrentStep()) return;
        currentStep += n;
        showStep(currentStep);
    }

    // --- SAVE DRAFT (AJAX) ---
    function saveDraft() {
        const form = document.getElementById('surveyForm');
        const formData = new FormData(form);
        formData.append('current_step', currentStep); 

        // Loading
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // Fetch API
        fetch("{{ route('survey.save_draft') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersimpan!',
                    text: 'Progress Anda aman. Anda bisa melanjutkannya nanti.',
                    confirmButtonColor: '#000',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: 'Terjadi kesalahan jaringan atau server.',
                confirmButtonColor: '#000'
            });
        });
    }

    // --- FINISH SURVEY (Pop Up) ---
    function finishSurvey() {
        if(!validateCurrentStep()) return;

        Swal.fire({
            title: '<strong>Konfirmasi Pengiriman</strong>',
            html: 'Apakah Anda yakin data sudah benar?<br>Data tidak dapat diubah setelah dikirim.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#000000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim Data',
            cancelButtonText: 'Cek Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengirim...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                document.getElementById('surveyForm').submit();
            }
        });
    }

    // --- VALIDATION UTILS ---
    function validateCurrentStep() {
        let activeStepDiv = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
        let inputs = activeStepDiv.querySelectorAll('input[required], select[required]');
        let valid = true;

        inputs.forEach(input => {
            // Skip jika pertanyaan disembunyikan oleh logic dependency
            if (input.closest('.logic-hidden')) return; 
            
            let isValidInput = true;
            if (input.type === 'radio') {
                if (!document.querySelector(`input[name="${input.name}"]:checked`)) isValidInput = false;
            } else {
                if (!input.value) isValidInput = false;
            }

            if(!isValidInput) {
                valid = false;
                // Visual feedback error
                if(input.closest('.selection-card')) {
                    const wrapper = input.closest('.d-flex.flex-column');
                    if(wrapper) {
                        wrapper.style.border = "1px solid #dc3545";
                        wrapper.style.borderRadius = "8px";
                        wrapper.style.padding = "10px";
                    }
                } else {
                    input.style.borderColor = "#dc3545";
                }
            } else {
                // Reset visual feedback
                if(input.closest('.selection-card')) {
                    const wrapper = input.closest('.d-flex.flex-column');
                    if(wrapper) {
                        wrapper.style.border = "none";
                        wrapper.style.padding = "0";
                    }
                } else {
                    input.style.borderColor = "#cccccc";
                }
            }
        });

        if(!valid) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Lengkap',
                text: 'Mohon lengkapi semua pertanyaan wajib (*).',
                confirmButtonColor: '#000',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
        return valid;
    }

    function updateSelectionStyles(input) {
        if (input.type === 'radio') {
            document.getElementsByName(input.name).forEach(el => {
                el.closest('.selection-card').classList.remove('selected');
            });
        }
        if(input.checked) input.closest('.selection-card').classList.add('selected');
        else input.closest('.selection-card').classList.remove('selected');
    }

    // --- LOGIC DEPENDENCY ---
    document.querySelectorAll('.logic-trigger').forEach(item => {
        item.addEventListener('change', runLogicCheck);
        item.addEventListener('input', runLogicCheck);
    });

    function runLogicCheck() {
        let dependentQuestions = document.querySelectorAll('.question-wrapper[data-depends-on]:not([data-depends-on=""])');
        
        dependentQuestions.forEach(child => {
            let parentId = child.getAttribute('data-depends-on');
            let requiredVal = child.getAttribute('data-depends-value');
            
            let parentCurrentVal = "";
            let radioCheck = document.querySelector(`input[type="radio"][data-qid="${parentId}"]:checked`);
            
            if(radioCheck) {
                parentCurrentVal = radioCheck.value;
            } else {
                let textInput = document.querySelector(`[data-qid="${parentId}"]`);
                if(textInput) parentCurrentVal = textInput.value;
            }

            if (parentCurrentVal === requiredVal) {
                child.classList.remove('logic-hidden');
            } else {
                child.classList.add('logic-hidden');
                // Reset value if hidden
                child.querySelectorAll('input, select').forEach(el => {
                    if(el.type === 'checkbox' || el.type === 'radio') {
                        el.checked = false;
                        el.closest('.selection-card')?.classList.remove('selected');
                    } else el.value = '';
                });
            }
        });
    }
</script>

</body>
</html>