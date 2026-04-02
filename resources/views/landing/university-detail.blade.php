@extends('landing.layouts.app')

@section('title', $university->university_name)

@push('styles')
<style>
    .university-header {
        background: var(--maroon-primary);
        color: var(--white);
        padding: 2.5rem 0;
    }

    .university-title {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        animation: fadeInUp 0.8s ease;
    }

    .university-subtitle {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.85);
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .back-link {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .content-section {
        padding: 2rem 0;
    }

    /* COMPACT 3 COLUMN LAYOUT */
    .info-grid {
        display: grid;
        grid-template-columns: 250px 1fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .logo-wrapper {
        width: 100%;
        max-width: 250px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
    }

    .logo-wrapper.no-logo {
        background: #d0d3d4;
    }

    .logo-wrapper .default-logo {
        width: 10rem;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }

    .logo-wrapper img {
        max-width: 100%;
        max-height: 180px;
        object-fit: contain;
    }

    .info-section {
        margin-bottom: 0;
    }

    .info-section h2 {
        color: var(--gray-700);
        font-size: 1.1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--gray-700);
    }

    .info-row {
        display: flex;
        padding: 0.5rem 0;
        font-size: 0.85rem;
        border-bottom: 1px solid var(--gray-200);
        transition: background-color 0.2s ease;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--gray-900);
        font-weight: 600;
        width: 45%;
        flex-shrink: 0;
        font-size: 0.85rem;
    }

    .info-value {
        color: var(--gray-700);
        flex: 1;
        font-size: 0.85rem;
    }

    /* COMBINED INFO COLUMN */
    .combined-info {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* FACILITIES COLUMN */
    .facility-icon-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .facility-icon-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .facility-icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--maroon-primary);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .facility-icon-circle i {
        font-size: 1.3rem;
    }

    .facility-label {
        color: var(--gray-900);
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.2;
    }

    .cta-section {
        text-align: center;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid var(--gray-200);
    }

    /* ANIMATIONS */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .info-grid {
            grid-template-columns: 220px 1fr 1fr;
            gap: 1.5rem;
        }

        .logo-wrapper {
            max-width: 220px;
            min-height: 180px;
        }

        .facility-icon-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .facility-icon-circle {
            width: 45px;
            height: 45px;
        }

        .facility-icon-circle i {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 991px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .logo-column {
            position: static;
            text-align: center;
        }

        .logo-wrapper {
            max-width: 300px;
            margin: 0 auto;
        }

        .combined-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .facility-icon-grid {
            grid-template-columns: repeat(5, 1fr);
        }

        .facility-icon-circle {
            width: 50px;
            height: 50px;
        }

        .facility-icon-circle i {
            font-size: 1.3rem;
        }
    }

    @media (max-width: 768px) {
        .university-header {
            padding: 2rem 0;
        }

        .university-title {
            font-size: 1.5rem;
        }

        .university-subtitle {
            font-size: 0.85rem;
            gap: 1rem;
        }

        .content-section {
            padding: 1.5rem 0;
        }

        .combined-info {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .info-section h2 {
            font-size: 1rem;
        }

        .info-row {
            flex-direction: column;
            padding: 0.6rem 0;
            font-size: 0.8rem;
        }

        .info-label {
            width: 100%;
            margin-bottom: 0.3rem;
            font-size: 0.8rem;
        }

        .info-value {
            font-size: 0.8rem;
        }

        .facility-icon-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
        }

        .facility-icon-circle {
            width: 40px;
            height: 40px;
        }

        .facility-icon-circle i {
            font-size: 1.1rem;
        }

        .facility-label {
            font-size: 0.65rem;
        }

        .cta-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .university-title {
            font-size: 1.35rem;
        }

        .university-subtitle {
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .logo-wrapper {
            min-height: 150px;
        }

        .logo-wrapper img {
            max-height: 130px;
        }

        .facility-icon-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
        }

        .facility-icon-circle {
            width: 38px;
            height: 38px;
        }

        .facility-icon-circle i {
            font-size: 1rem;
        }

        .facility-label {
            font-size: 0.6rem;
        }
    }
</style>
@endpush

@section('content')

<section class="university-header">
    <div class="container">
        <h1 class="university-title">{{ $university->university_name }}</h1>
        
        <div class="university-subtitle">
            <span><i class="bi bi-mortarboard"></i> {{ $university->university_type }}</span>
            <span><i class="bi bi-building"></i> {{ $university->university_category }}</span>
            @if($university->phone_number)
                <span><i class="bi bi-telephone-fill"></i> {{ $university->phone_number }}</span>
            @endif
        </div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="info-grid">
            
            <!-- LOGO COLUMN -->
            <div class="logo-column">
                <div class="logo-wrapper {{ !$university->university_logo ? 'no-logo' : '' }}">
                    @if($university->university_logo)
                        <img src="{{ asset('storage/' . $university->university_logo) }}" 
                             alt="{{ $university->university_name }}">
                    @else
                        <img src="{{ asset('images/ideahub-logo.png') }}" 
                             alt="IdeaHub"
                             class="default-logo">
                    @endif
                </div>
            </div>

            <!-- COMBINED INFO COLUMN (Info Umum + ULD) -->
            <div class="combined-info">
                <!-- Info Umum -->
                <div class="info-section">
                    <h2><i class="bi bi-info-circle-fill me-2"></i>Informasi Umum</h2>
                    
                    <div class="info-row">
                        <div class="info-label">Nama PT</div>
                        <div class="info-value">{{ $university->university_name }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Jenis</div>
                        <div class="info-value">{{ $university->university_type }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">{{ $university->university_category }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Prodi PLB</div>
                        <div class="info-value">
                            {{ $university->has_disability_study_program ? 'Ya' : 'Tidak' }}
                        </div>
                    </div>
                    
                    @if($university->phone_number)
                        <div class="info-row">
                            <div class="info-label">Kontak</div>
                            <div class="info-value">{{ $university->phone_number }}</div>
                        </div>
                    @endif
                </div>

                <!-- ULD -->
                @php
                    $q9 = $questions->get('q9');
                    $hasULD = $q9 && isset($answers[$q9->id]) ? $answers[$q9->id] : 'N/A';
                @endphp

                <div class="info-section">
                    <h2><i class="bi bi-hospital-fill me-2"></i>Unit Layanan Disabilitas</h2>
                    
                    <div class="info-row">
                        <div class="info-label">Ketersediaan ULD</div>
                        <div class="info-value">
                            @if($hasULD === 'Sudah')
                                <span style="color: #28a745; font-weight: 600; font-size: 0.85rem;">
                                    <i class="bi bi-check-circle-fill"></i> Tersedia
                                </span>
                            @else
                                <span style="color: #dc3545; font-weight: 600; font-size: 0.85rem;">
                                    <i class="bi bi-x-circle-fill"></i> Belum
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- FACILITIES COLUMN -->
            <div class="info-section">
                <h2><i class="bi bi-patch-check me-2"></i>Fasilitas Pendukung</h2>

                @php
                    $q38 = $questions->get('q38');
                    $facilities = $q38 && isset($answers[$q38->id]) ? $answers[$q38->id] : [];
                    if (!is_array($facilities)) {
                        $facilities = [];
                    }
                    
                    $facilityIcons = [
                        'Petunjuk Braille' => 'bi-eye-slash',
                        'Blok pemandu' => 'bi-signpost',
                        'Toilet disabilitas' => 'bi-person-wheelchair',
                        'Lift' => 'bi-arrow-up-square',
                        'Ramp' => 'bi-arrow-up-right',
                        'Parkir disabilitas' => 'bi-p-square',
                        'Layanan mobilitas' => 'bi-bus-front',
                        'Text-to-Speech' => 'bi-volume-up',
                        'Screen reader' => 'bi-eye',
                        'Alat bantu dengar' => 'bi-ear',
                        'LMS Aksesibel' => 'bi-laptop',
                        'Peta interaktif' => 'bi-map',
                        'Lainnya' => 'bi-three-dots',
                    ];
                @endphp

                @if(is_array($facilities) && count($facilities))
                    <div class="facility-icon-grid">
                        @foreach($facilities as $facility)
                            <div class="facility-icon-item">
                                <div class="facility-icon-circle">
                                    <i class="bi {{ $facilityIcons[$facility] ?? 'bi-three-dots' }}"></i>
                                </div>
                                <div class="facility-label">{{ $facility }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary" style="margin-top: 1rem; font-size: 0.85rem;">Data belum tersedia</p>
                @endif
            </div>

        </div>

        <!-- CTA -->
        <div class="cta-section">
            <p class="text-secondary mb-3" style="font-size: 0.9rem;">Kampus Anda Belum Terdata?</p>
            <a href="{{ route('register') }}" class="btn-outline-custom">
                Laporkan Kampus Anda <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

@endsection