@extends('landing.layouts.app')

@section('title', $university->university_name)

@push('styles')
<style>
    .university-header {
        background: var(--black);
        color: var(--white);
        padding: 3rem 0;
    }

    .university-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .university-subtitle {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.85);
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .back-link {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .back-link:hover {
        color: var(--white);
    }

    .content-section {
        padding: 3rem 0;
    }

    .info-card {
        border: 2px solid var(--black);
        padding: 2rem;
        margin-bottom: 2rem;
        background: var(--white);
    }

    .info-card h4 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--black);
    }

    .info-row {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        width: 250px;
        flex-shrink: 0;
    }

    .info-value {
        color: var(--gray-700);
    }

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .facility-item {
        border: 2px solid var(--black);
        padding: 1rem;
        text-align: center;
        background: var(--white);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .facility-item i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .disability-types-table {
        width: 100%;
        margin-top: 1rem;
    }

    .disability-types-table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .disability-types-table td:first-child {
        font-weight: 600;
        width: 60%;
    }

    .disability-types-table td:last-child {
        text-align: right;
        font-weight: 700;
        color: var(--black);
    }

    .disability-accordion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .disability-accordion-header i {
        transition: transform 0.3s;
    }

    .disability-accordion-header.active i {
        transform: rotate(180deg);
    }

    .disability-accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    .disability-accordion-content.show {
        max-height: 600px;
        margin-top: 1.5rem;
    }

    .facility-icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .facility-icon-item {
        /* border: 2px solid var(--black); */
        padding: 1rem;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .facility-icon-item i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    @media (max-width: 768px) {
        .university-title {
            font-size: 1.75rem;
        }

        .info-row {
            flex-direction: column;
        }

        .info-label {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@section('content')

<!-- HEADER -->
<section class="university-header">
    <div class="container">
        <a href="{{ route('landing.universities') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Browse Universities
        </a>
        
        <h1 class="university-title">{{ $university->university_name }}</h1>
        
        <div class="university-subtitle">
            <span><i class="bi bi-geo-alt-fill"></i> {{ $university->university_type }}</span>
            <span><i class="bi bi-building"></i> {{ $university->university_category }}</span>
            @if($university->phone_number)
                <span><i class="bi bi-telephone-fill"></i> {{ $university->phone_number }}</span>
            @endif
        </div>
    </div>
</section>

<!-- CONTENT -->
<section class="content-section">
    <div class="container">
        
        <!-- BASIC INFO -->
        <div class="info-card">
            <h4><i class="bi bi-info-circle-fill me-2"></i>Informasi Umum</h4>
            
            <div class="info-row">
                <div class="info-label">Nama Perguruan Tinggi</div>
                <div class="info-value">{{ $university->university_name }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Jenis PT</div>
                <div class="info-value">{{ $university->university_type }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Kategori</div>
                <div class="info-value">{{ $university->university_category }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Program Studi PLB/Pendidikan Khusus</div>
                <div class="info-value">
                    {{ $university->has_disability_study_program ? 'Ya, tersedia' : 'Tidak tersedia' }}
                </div>
            </div>
            
            @if($university->phone_number)
                <div class="info-row">
                    <div class="info-label">Kontak</div>
                    <div class="info-value">{{ $university->phone_number }}</div>
                </div>
            @endif
        </div>

        <!-- DISABILITY STATISTICS -->
        @php
            $q6 = $questions->get('q6');
            $q6a = $questions->get('q6a');
            $totalDisability = $q6 && isset($answers[$q6->id]) ? $answers[$q6->id] : 'N/A';
            $disabilitas = $answers[$q6a->id] ?? [];
            // $disabilityTypes = $q6a && isset($answers[$q6a->id]) ? $answers[$q6a->id] : [];
            $disabilityTypes = [];
            if ($q6a && isset($answers[$q6a->id])) {
                if (is_string($answers[$q6a->id])) {
                    $decoded = json_decode($answers[$q6a->id], true);
                    $disabilityTypes = is_array($decoded) ? $decoded : [];
                } elseif (is_array($answers[$q6a->id])) {
                    $disabilityTypes = $answers[$q6a->id];
                }
            }
        @endphp

        @php
            $labelDisabilitas = [
                0 => 'Tunanetra Total',
                1 => 'Low Vision',
                2 => 'Buta Warna',
                3 => 'Tunarungu Total',
                4 => 'Kesulitan Mendengar',
                5 => 'Disabilitas fisik sedang',
                6 => 'Disabilitas fisik berat',
                7 => 'Disabilitas fisik ringan',
                8 => 'Disabilitas intelektual',
                9 => 'Spektrum autisme',
                10 => 'ADHD',
                11 => 'Gangguan Psikososial',
                12 => 'Multidisabilitas',

            ];
        @endphp

        <div class="info-card">
            <div class="disability-accordion-header" id="disabilityToggle">
                <h4 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>
                    Data Mahasiswa Disabilitas
                </h4>

                <div class="d-flex align-items-center gap-3">
                    <strong style="font-size: 1.25rem;">{{ $totalDisability }}</strong>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>

            <div class="disability-accordion-content" id="disabilityContent">
                @if(is_array($disabilitas) && count($disabilitas))
                    <table class="disability-types-table">
                        @foreach($disabilitas as $kode => $jumlah)
                            @if($jumlah > 0)
                                <tr>
                                    <td>{{ $labelDisabilitas[$kode] ?? 'Lainnya' }}</td>
                                    <td>{{ $jumlah }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                @else
                    <p class="text-secondary">Data rincian belum tersedia</p>
                @endif
            </div>
        </div>


        <!-- ULD -->
        @php
            $q9 = $questions->get('q9');
            $hasULD = $q9 && isset($answers[$q9->id]) ? $answers[$q9->id] : 'N/A';
        @endphp

        <div class="info-card">
            <h4><i class="bi bi-hospital-fill me-2"></i>Unit Layanan Disabilitas</h4>
            
            <div class="info-row">
                <div class="info-label">Ketersediaan ULD atau Unit Serupa</div>
                <div class="info-value">
                    @if($hasULD === 'Sudah')
                        <span style="color: #28a745; font-weight: 600;">
                            <i class="bi bi-check-circle-fill"></i> Sudah Tersedia
                        </span>
                    @else
                        <span style="color: #dc3545; font-weight: 600;">
                            <i class="bi bi-x-circle-fill"></i> Belum Tersedia
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- FACILITIES -->
        @php
            $q38 = $questions->get('q38');
            $facilities = $q38 && isset($answers[$q38->id]) ? $answers[$q38->id] : [];
            if (!is_array($facilities)) {
                $facilities = [];
            }
        @endphp
        @php
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

        <div class="info-card">
            <h4><i class="bi bi-hammer me-2"></i>Fasilitas Pendukung</h4>

            @if(is_array($facilities) && count($facilities))
                <div class="facility-icon-grid">
                    @foreach($facilities as $facility)
                        <div class="facility-icon-item">
                            <i class="bi {{ $facilityIcons[$facility] ?? 'bi-three-dots' }}"></i>
                            {{ $facility }}
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-secondary" style="margin-top: 1rem;">Data fasilitas belum tersedia</p>
            @endif
        </div>

        <!-- CTA -->
        <div class="text-center mt-5 pt-4" style="border-top: 2px solid var(--gray-200);">
            <p class="text-secondary mb-3">Tertarik untuk mendaftar?</p>
            <a href="{{ route('register') }}" class="btn-primary-custom">
                Daftarkan Kampus Anda <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<script>
    const disabilityToggle = document.getElementById('disabilityToggle');
    const disabilityContent = document.getElementById('disabilityContent');

    disabilityToggle.addEventListener('click', () => {
        disabilityToggle.classList.toggle('active');
        disabilityContent.classList.toggle('show');
    });
</script>

@endsection