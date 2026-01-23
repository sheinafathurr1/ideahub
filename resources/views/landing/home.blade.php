@extends('landing.layouts.app')

@section('title', 'Home')

@push('styles')
<style>
    /* HERO SECTION */
    .hero-section {
        background: var(--black);
        color: var(--white);
        padding: 5rem 0;
        text-align: center;
    }

    .hero-title {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        font-weight: 800;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255,255,255,0.85);
        margin-bottom: 2rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* STATS */
    .stats-section {
        padding: 3rem 0;
        background: var(--gray-100);
    }

    .stat-card {
        text-align: center;
        padding: 2rem;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: var(--black);
    }

    .stat-label {
        font-size: 1rem;
        color: var(--gray-700);
        margin-top: 0.5rem;
    }

    /* BROWSE UNIVERSITIES */
    .browse-section {
        padding: 4rem 0;
    }

    .university-card {
        border: 2px solid var(--black);
        padding: 2rem;
        height: 100%;
        transition: all 0.3s;
        background: var(--white);
    }

    .university-card:hover {
        transform: translateY(-5px);
        box-shadow: 8px 8px 0 var(--black);
    }

    .university-card h5 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .university-card .badge {
        background: var(--black);
        color: var(--white);
        padding: 0.25rem 0.75rem;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* NEWS SECTION */
    .news-section {
        padding: 4rem 0;
        background: var(--gray-100);
    }

    .news-card {
        border: 2px solid var(--black);
        overflow: hidden;
        height: 100%;
        background: var(--white);
        transition: all 0.3s;
    }

    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 8px 8px 0 var(--black);
    }

    .news-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .news-card-body {
        padding: 1.5rem;
    }

    .news-card h5 {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }

    .news-card-date {
        font-size: 0.85rem;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }

    /* ABOUT SECTION */
    .about-section {
        padding: 4rem 0;
    }

    .about-logo {
        width: 200px;
        height: 200px;
        background: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--black);
    }

    .about-logo i {
        font-size: 5rem;
        color: var(--black);
    }

    /* PARTNERS SECTION */
    .partners-section {
        padding: 3rem 0;
        background: var(--gray-100);
    }

    .partner-placeholder {
        border: 2px dashed var(--gray-300);
        padding: 3rem;
        text-align: center;
        color: var(--gray-700);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')

<!-- HERO -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Platform Inklusi Disabilitas Indonesia</h1>
        <p class="hero-subtitle">
            Temukan universitas inklusif yang mendukung mahasiswa disabilitas dengan fasilitas dan layanan terbaik
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('landing.universities') }}" class="btn-primary-custom">
                Browse Universities
            </a>
            <a href="{{ route('register') }}" class="btn-outline-custom">
                Daftarkan Kampus Anda
            </a>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalUniversities }}</div>
                    <div class="stat-label">Universitas Terdaftar</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Jenis Fasilitas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Dimensi Inklusi</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BROWSE UNIVERSITIES PREVIEW -->
<section class="browse-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Universitas Inklusif</h2>
            <p class="section-subtitle">Jelajahi universitas yang berkomitmen terhadap inklusi disabilitas</p>
        </div>

        <div class="text-center mb-5">
            <a href="{{ route('landing.universities') }}" class="btn-primary-custom">
                Lihat Semua Universitas <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- NEWS/BERITA -->
<section class="news-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Berita & Insight</h2>
            <p class="section-subtitle">Update terbaru seputar inklusi disabilitas di perguruan tinggi</p>
        </div>

        <div class="row g-4 mb-4">
            @forelse($latestNews->take(3) as $news)
                <div class="col-md-4">
                    <a href="{{ route('landing.news-detail', $news->slug) }}" class="text-decoration-none">
                        <div class="news-card">
                            @if($news->featured_image)
                                <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}">
                            @else
                                <div style="width: 100%; height: 200px; background: var(--gray-200); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-newspaper" style="font-size: 3rem; color: var(--gray-700);"></i>
                                </div>
                            @endif
                            
                            <div class="news-card-body">
                                <div class="news-card-date">
                                    <i class="bi bi-calendar3"></i> {{ $news->published_at->format('d M Y') }}
                                </div>
                                <h5 class="text-dark">{{ $news->title }}</h5>
                                @if($news->excerpt)
                                    <p class="text-secondary mb-0" style="font-size: 0.9rem;">
                                        {{ Str::limit($news->excerpt, 100) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-secondary py-5">
                        <i class="bi bi-newspaper" style="font-size: 3rem;"></i>
                        <p class="mt-3">Belum ada berita tersedia</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($latestNews->count() > 0)
            <div class="text-center">
                <a href="{{ route('landing.news') }}" class="btn-outline-custom">
                    Lihat Semua Berita <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        @endif
    </div>
</section>

<!-- ABOUT IDEAHUB -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="about-logo mx-auto">
                    <i class="bi bi-grid-fill"></i>
                </div>
            </div>
            <div class="col-md-8">
                <h2 class="section-title">Tentang IdeaHub</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; color: var(--gray-700);">
                    IdeaHub adalah platform pendataan dan informasi universitas inklusif di Indonesia yang mendukung 
                    mahasiswa disabilitas. Kami mengumpulkan data komprehensif tentang fasilitas, layanan, dan komitmen 
                    perguruan tinggi terhadap inklusi disabilitas berdasarkan 10 dimensi inklusi.
                </p>
                <p style="font-size: 1.1rem; line-height: 1.8; color: var(--gray-700);">
                    Misi kami adalah membantu calon mahasiswa disabilitas menemukan lingkungan pendidikan yang 
                    mendukung kebutuhan mereka, serta mendorong perguruan tinggi untuk terus meningkatkan 
                    komitmen terhadap aksesibilitas dan inklusi.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- PARTNERS -->
<section class="partners-section">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Didukung Oleh</h2>
        </div>
        <div class="partner-placeholder">
            <i class="bi bi-building" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-0">Partner logos akan ditampilkan di sini</p>
        </div>
    </div>
</section>

@endsection