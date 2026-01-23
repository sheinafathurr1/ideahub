@extends('landing.layouts.app')

@section('title', 'Browse Universities')

@push('styles')
<style>
    .page-header {
        background: var(--black);
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
        /* margin-bottom: 2rem; */
        /* border-bottom: 2px solid var(--black); */
    }

    .filter-toggle {
        background: var(--black);
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
        transition: all 0.2s;
    }

    .filter-toggle:hover {
        background: var(--gray-900);
    }

    .filter-toggle i {
        transition: transform 0.3s;
    }

    .filter-toggle.active i {
        transform: rotate(180deg);
    }

    .filter-content {
        max-height: 0;
        overflow: hidden;
        background: var(--gray-100);
        padding: 0 2rem;
        transition: max-height 0.4s ease-out, padding 0.2s ease-out;
    }

    .filter-content.show {
        max-height: 600px;
        padding: 2rem;
    }

    .support-filters {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 1.5rem;
    }

    .support-filter-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem;
        background: var(--white);
        border: 2px solid var(--black);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--black);
    }

    /* .support-filter-item:hover {
        transform: translateY(-3px);
        box-shadow: 4px 4px 0 var(--black);
        color: var(--black);
    } */

    .support-filter-item.active {
        background: var(--black);
        color: var(--white);
    }

    .support-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.5rem;
        transition: all 0.2s;
    }

    .support-filter-item.active .support-icon {
        background: var(--white);
        color: var(--black);
    }

    .support-label {
        font-weight: 600;
        font-size: 0.75rem;
        line-height: 1.2;
    }

    /* UNIVERSITIES GRID */
    .universities-grid {
        padding: 3rem 0;
    }

    .university-card {
        border: 2px solid var(--black);
        overflow: hidden;
        transition: all 0.3s;
        background: var(--white);
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
    }

    .university-card:hover {
        transform: translateY(-5px);
        box-shadow: 8px 8px 0 var(--black);
        color: inherit;
    }

    .university-logo {
        width: 100%;
        height: 200px;
        background: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 2px solid var(--black);
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
    }

    .university-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--black);
        line-height: 1.3;
        text-align: center;
    }

    .university-meta {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .university-badge {
        background: var(--black);
        color: var(--white);
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* FACILITY ICONS IN CARD */
    .university-facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    .facility-icon-small {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: var(--black);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        border: 2px solid var(--black);
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

    .pagination {
        margin-top: 3rem;
    }

    .pagination .page-link {
        border: 2px solid var(--black);
        color: var(--black);
        font-weight: 600;
        margin: 0 0.25rem;
        padding: 0.5rem 1rem;
    }

    .pagination .page-link:hover {
        background: var(--black);
        color: var(--white);
    }

    .pagination .page-item.active .page-link {
        background: var(--black);
        border-color: var(--black);
    }

    @media (max-width: 768px) {
        .support-filters {
            grid-template-columns: repeat(3, 1fr);
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
                        ['name' => 'Lainnya', 'icon' => 'bi-three-dots'],
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
                        
                        // Q38: Fasilitas
                        $q38 = \App\Models\Question::where('code', 'q38')->first();
                        $universityFacilities = [];
                        
                        if ($q38 && isset($answers[$q38->id])) {
                            $decoded = json_decode($answers[$q38->id], true);
                            $universityFacilities = is_array($decoded) ? $decoded : [];
                        }
                        
                        // Mapping fasilitas ke icon
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

                    <div class="col-md-4">
                        <a href="{{ route('landing.university-detail', $university->slug) }}" class="university-card">
                            <!-- Logo Placeholder -->
                            <div class="university-logo">
                                <i class="bi bi-building"></i>
                            </div>

                            <!-- Card Body -->
                            <div class="university-card-body">
                                <h3 class="university-name">{{ $university->university_name }}</h3>
                                
                                <div class="university-meta">
                                    <span class="university-badge">{{ $university->university_type }}</span>
                                    <span class="university-badge">{{ $university->university_category }}</span>
                                </div>

                                <!-- Facility Icons -->
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

        <!-- Pagination -->
        @if($universities->hasPages())
            <div class="d-flex justify-content-center">
                {{ $universities->links() }}
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Toggle filter accordion
    const filterToggle = document.getElementById('filterToggle');
    const filterContent = document.getElementById('filterContent');
    
    filterToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        filterContent.classList.toggle('show');
    });
</script>
@endpush