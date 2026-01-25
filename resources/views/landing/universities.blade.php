@extends('landing.layouts.app')
@section('title', 'Browse Universities')
@push('styles')
<style>
    .page-header {
        background: var(--maroon-primary);
        color: var(--white);
        padding: 3rem 0;
        text-align: center;
    }
    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* FILTER SECTION */
    .filter-section {
        padding: 2rem 0;
        background: var(--white);
    }
    .filter-toggle {
        background: var(--maroon-primary);
        color: var(--white);
        border: none;
        padding: 1rem 2rem;
        font-weight: 700;
        font-size: 1.1rem;
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-toggle.active i {
        transform: rotate(180deg);
    }
    .filter-content {
        max-height: 0;
        overflow: hidden;
        padding: 0 1rem;
        background: var(--maroon-primary);
        opacity: 0;
        transition: max-height 0.4s ease, opacity 0.25s ease, padding 0.3s ease;
    }
    .filter-content.show {
        max-height: 600px;
        padding: 2rem 1rem;
        opacity: 1;
    }
    .support-filters {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 0;
    }
    .support-filter-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem;
        background: transparent;
        color: var(--white);
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .support-filter-item.active {
        background: var(--maroon-primary);
        color: var(--orange-primary);
        transform: scale(1.05);
        z-index: 2;
    }
    .support-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--white);
        color: var(--maroon-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }
    .support-filter-item.active .support-icon {
        background: var(--orange-primary);
        color: var(--white);
    }
    .support-label {
        font-weight: 600;
        font-size: 0.7rem;
        line-height: 1.2;
    }
    
    /* UNIVERSITIES GRID */
    .universities-grid {
        padding: 3rem 0;
    }
    .university-card {
        overflow: hidden;
        background: var(--maroon-primary);
        color: var(--white);
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .university-logo {
        width: 100%;
        height: 200px;
        background: #d0d3d4;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .university-logo i {
        font-size: 4rem;
        color: var(--gray-700);
    }
    .university-card-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: var(--maroon-primary);
        color: var(--white);
    }
    .university-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--white);
        line-height: 1.3;
        text-align: center;
    }
    .university-meta {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        justify-content: center;
    }
    .university-badge {
        background: var(--orange-primary);
        color: var(--maroon-primary);
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .university-facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.2);
        justify-content: center;
    }
    .facility-icon-small {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: var(--white);
        color: var(--maroon-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .no-results {
        text-align: center;
        padding: 4rem 2rem;
    }
    .no-results i {
        font-size: 4rem;
        color: var(--gray-300);
        margin-bottom: 1rem;
    }
    
    /* CUSTOM PAGINATION */
    .pagination-wrapper {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .pagination .page-item {
        margin: 0;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        height: 45px;
        padding: 0.5rem 0.75rem;
        border: 2px solid var(--maroon-primary);
        background: var(--white);
        color: var(--maroon-primary);
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 0;
    }

    .page-item:first-child .page-link{
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .page-item:last-child .page-link{
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .pagination .page-link:hover {
        background: var(--maroon-primary);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .pagination .page-item.active .page-link {
        background: var(--orange-primary);
        color: var(--white);
        border-color: var(--orange-primary);
    }
    
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .pagination .page-link svg {
        width: 16px;
        height: 16px;
    }
    
    /* RESPONSIVE */
    @media (max-width: 991px) {
        .support-filters {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0;
        }
        .page-header h1 {
            font-size: 2rem;
        }
        .filter-toggle {
            font-size: 1rem;
            padding: 0.875rem 1.5rem;
        }
        .support-filters {
            grid-template-columns: repeat(3, 1fr);
        }
        .support-filter-item {
            padding: 0.75rem;
        }
        .support-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        .support-label {
            font-size: 0.65rem;
        }
        .universities-grid {
            padding: 2rem 0;
        }
        .university-logo {
            height: 180px;
        }
        .university-name {
            font-size: 1.1rem;
        }
        
        .pagination-wrapper {
            margin-top: 2rem;
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            min-width: 38px;
            height: 38px;
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.75rem;
        }
        .support-filters {
            grid-template-columns: repeat(2, 1fr);
        }
        .support-filter-item {
            padding: 0.65rem;
        }
        .university-logo {
            height: 160px;
        }
        .university-card-body {
            padding: 1.25rem;
        }
        
        .pagination .page-link {
            min-width: 35px;
            height: 35px;
            padding: 0.35rem 0.5rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush
@section('content')
<!-- HEADER -->
<section class="page-header">
    <div class="container">
        <h1>Browse all universities</h1>
        <p style="font-size: 1.1rem; color: rgba(255,255,255,0.85);">
            Direktori perguruan tinggi Indonesia yang menyediakan dukungan lebih untuk mahasiswa disabilitas
        </p>
    </div>
</section>

<!-- FILTER SECTION -->
<section class="filter-section">
    <div class="container">
        <button class="filter-toggle" id="filterToggle">
            <span>By Type of Support</span>
            <i class="bi bi-chevron-down"></i>
        </button>
        
        <div class="filter-content" id="filterContent">
            <div class="support-filters">
                @php
                    $facilities = [
                        ['name' => 'Petunjuk Braille', 'icon' => 'bi-eye-slash'],
                        ['name' => 'Blok pemandu', 'icon' => 'bi-signpost'],
                        ['name' => 'Toilet disabilitas', 'icon' => 'bi-person-wheelchair'],
                        ['name' => 'Lift', 'icon' => 'bi-arrow-up-square'],
                        ['name' => 'Ramp', 'icon' => 'bi-arrow-up-right'],
                        ['name' => 'Parkir disabilitas', 'icon' => 'bi-p-square'],
                        ['name' => 'Layanan mobilitas', 'icon' => 'bi-bus-front'],
                        ['name' => 'Text-to-Speech', 'icon' => 'bi-volume-up'],
                        ['name' => 'Screen reader', 'icon' => 'bi-eye'],
                        ['name' => 'Alat bantu dengar', 'icon' => 'bi-ear'],
                        ['name' => 'LMS Aksesibel', 'icon' => 'bi-laptop'],
                        ['name' => 'Peta interaktif', 'icon' => 'bi-map'],
                    ];
                    $activeFilter = request('support');
                @endphp
                @foreach($facilities as $facility)
                    <a href="{{ $activeFilter === $facility['name'] ? route('landing.universities') : route('landing.universities', ['support' => $facility['name']]) }}" 
                       class="support-filter-item {{ $activeFilter === $facility['name'] ? 'active' : '' }}">
                        <div class="support-icon">
                            <i class="bi {{ $facility['icon'] }}"></i>
                        </div>
                        <div class="support-label">{{ $facility['name'] }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- RESULTS -->
<section class="universities-grid">
    <div class="container">
        
        <!-- Results Count -->
        <div class="mb-4">
            <p class="text-secondary fw-bold">
                Menampilkan {{ $universities->total() }} universitas
                @if($activeFilter)
                    dengan fasilitas "{{ $activeFilter }}"
                @endif
            </p>
        </div>

        <!-- University Grid -->
        @if($universities->count() > 0)
            <div class="row g-4">
                @foreach($universities as $university)
                    @php
                        $submission = $university->acceptedSubmission;
                        $answers = $submission->values->pluck('value', 'question_id')->toArray();
                        
                        $q38 = \App\Models\Question::where('code', 'q38')->first();
                        $universityFacilities = [];
                        
                        if ($q38 && isset($answers[$q38->id])) {
                            $decoded = json_decode($answers[$q38->id], true);
                            $universityFacilities = is_array($decoded) ? $decoded : [];
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
                            'Screen reader' => 'bi-display',
                            'Alat bantu dengar' => 'bi-ear',
                            'LMS Aksesibel' => 'bi-laptop',
                            'Peta interaktif' => 'bi-map',
                            'Lainnya' => 'bi-three-dots',
                        ];
                    @endphp
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ route('landing.university-detail', $university->slug) }}" class="university-card" target="_blank" rel="noopener noreferrer">
                            <div class="university-logo">
                                @if($university->university_logo)
                                    <img src="{{ asset('storage/' . $university->university_logo) }}" 
                                         alt="{{ $university->university_name }}"
                                         style="width: 100%; height: 100%; object-fit: contain; padding: 1rem;">
                                @else
                                    <img src="{{ asset('images/ideahub-logo.png') }}" alt="IdeaHub" style="width: 10rem; object-fit: contain; filter: grayscale(100%) brightness(0) invert(1);">
                                @endif
                            </div>
                            <div class="university-card-body">
                                <h3 class="university-name">{{ $university->university_name }}</h3>
                                
                                <div class="university-meta">
                                    <span class="university-badge">{{ $university->university_type }}</span>
                                    <span class="university-badge">{{ $university->university_category }}</span>
                                </div>
                                <div class="university-facilities">
                                    @foreach($universityFacilities as $facility)
                                        @if(isset($facilityIcons[$facility]))
                                            <div class="facility-icon-small" title="{{ $facility }}">
                                                <i class="bi {{ $facilityIcons[$facility] }}"></i>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            @if($universities->hasPages())
                <div class="pagination-wrapper">
                    {{ $universities->links() }}
                </div>
            @endif
        @else
            <div class="no-results">
                <i class="bi bi-inbox"></i>
                <h4>Tidak ada universitas ditemukan</h4>
                <p class="text-secondary">
                    @if($activeFilter)
                        Tidak ada universitas dengan fasilitas "{{ $activeFilter }}"
                    @else
                        Belum ada data universitas tersedia
                    @endif
                </p>
                @if($activeFilter)
                    <a href="{{ route('landing.universities') }}" class="btn-primary-custom mt-3">
                        Lihat Semua Universitas
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    const filterToggle = document.getElementById('filterToggle');
    const filterContent = document.getElementById('filterContent');
    
    filterToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        filterContent.classList.toggle('show');
    });
</script>
@endpush