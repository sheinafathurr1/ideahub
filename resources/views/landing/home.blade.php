@extends('landing.layouts.app')

@section('title', 'Home')

@push('styles')
<style>
    /* HERO SECTION */
    .hero-section {
        background: var(--maroon-primary);
        color: var(--white);
        padding: 5rem 0;
        text-align: center;
    }

    .hero-title {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        font-weight: 800;
        animation: fadeInUp 0.8s ease;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255,255,255,0.85);
        margin-bottom: 2rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        animation: fadeInUp 1s ease;
    }

    /* NEWS SECTION */
    .news-section {
        padding: 4rem 0;
    }

    .news-card {
        overflow: hidden;
        height: 100%;
        background: var(--maroon-primary);
        color: var(--white);
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .news-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .news-card-body {
        padding: 1.5rem;
    }

    .news-card h5 {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        color: var(--white);
    }

    .news-card-date {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
        margin-bottom: 0.5rem;
    }

    .news-card-link {
        color: var(--white);
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .btn-news {
        background: var(--maroon-primary);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-news:hover {
        background: var(--orange-primary);
        color: var(--maroon-primary);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* ABOUT SECTION */
    .about-section {
        padding: 4rem 0;
    }

    .about-logo {
        width: 200px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .about-logo:hover {
        transform: scale(1.05);
    }

    .about-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .about-logo i {
        font-size: 5rem;
        color: var(--maroon-primary);
    }

    /* STAFF GUIDE SECTION */
    .staff-guide-section {
        padding: 3rem 0;
    }

    .staff-guide-content {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    .staff-guide-content h2 {
        color: var(--gray-900);
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .staff-guide-content p {
        font-size: 1rem;
        color: var(--gray-700);
        margin-bottom: 1.5rem;
    }

    .staff-guide-content a {
        background: var(--maroon-primary);
        color: var(--white);
        padding: 1rem 2rem;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .staff-guide-content a:hover {
        background: var(--orange-primary);
        color: var(--maroon-primary);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* FUNDED BY SECTION */
    .funded-by-section {
        background: var(--white);
        color: black;
        padding: 5rem 0;
    }

    .funded-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .partners-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        align-items: center;
    }

    .partner-logo {
        display: flex;
        justify-content: left;
        align-items: center;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .partner-logo img {
        max-height: 50px;
        max-width: 100%;
        transition: transform 0.3s ease;
    }

    .partner-logo:hover {
        opacity: 1;
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
    @media (max-width: 991px) {
        .partners-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 3rem 0;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            padding: 0 1rem;
        }

        .news-section {
            padding: 3rem 0;
        }

        .about-section {
            padding: 3rem 0;
        }

        .about-logo {
            width: 150px;
            height: 150px;
            margin-bottom: 2rem;
        }

        .staff-guide-section {
            padding: 2rem 0;
        }

        .staff-guide-content h2 {
            font-size: 1.5rem;
        }

        .staff-guide-content p {
            font-size: 0.95rem;
        }

        .funded-by-section {
            padding: 3rem 0;
        }

        .funded-title {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .partners-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .partner-logo img {
            max-height: 40px;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 1.75rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .news-card img {
            height: 180px;
        }

        .news-card-body {
            padding: 1.25rem;
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

<!-- NEWS/BERITA -->
<section class="news-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">News & Insight</h2>
            <p class="section-subtitle">Update terbaru seputar inklusi disabilitas di perguruan tinggi</p>
        </div>

        <div class="row g-4 mb-4">
            @forelse($latestNews->take(3) as $news)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('landing.news-detail', $news->slug) }}" class="news-card">
                        @if($news->featured_image)
                            <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}">
                        @else
                            <div style="width: 100%; height: 200px; background: var(--gray-300); display: flex; align-items: center; justify-content: center;">
                                <img src="{{ asset('images/ideahub-logo.png') }}" alt="IdeaHub" style="width: 10rem; object-fit: contain; filter: grayscale(100%) brightness(0) invert(1);">
                            </div>
                        @endif
                        
                        <div class="news-card-body">
                            <div class="news-card-date">
                                <i class="bi bi-calendar3"></i> {{ $news->published_at->format('d M Y') }}
                            </div>
                            <h5>{{ $news->title }}</h5>
                            @if($news->excerpt)
                                <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-bottom: 0.75rem;">
                                    {{ Str::limit($news->excerpt, 100) }}
                                </p>
                            @endif
                            <span class="news-card-link">Baca selengkapnya →</span>
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
                <a href="{{ route('landing.news') }}" class="btn-news">
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
                    @if(file_exists(public_path('images/ideahub-logo.png')))
                        <img src="{{ asset('images/ideahub-logo.png') }}" alt="IdeaHub">
                    @else
                        <i class="bi bi-grid-fill"></i>
                    @endif
                </div>
            </div>
            <div class="col-md-8">
                <h2 class="section-title">Tentang IdeaHub</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; color: var(--gray-700); text-align: justify;">
                    platform yang menyediakan informasi tentang pendidikan tinggi di Indonesia. 
                    Kami hadir untuk membawa informasi yang aksesibel tentang pendidikan tinggi bagi seluruh mahasiswa di Indonesia
                </p>
            </div>
        </div>
    </div>
</section>

<!-- STAFF GUIDE SECTION -->
<section class="staff-guide-section">
    <div class="container">
        <div class="staff-guide-content">
            <h2>Anda adalah staf perguruan tinggi?</h2>
            <p>
                Unduh buku panduan staf untuk cari tahu cara mewujudkan inklusi-disabilitas di kampus Anda!
            </p>
            <a href="https://drive.google.com/file/d/1AVk7bg_C2WdPE3T-8BKaBOKltU64BmA7/view">
                <i class="bi bi-download"></i>
                DOWNLOAD PANDUAN
            </a>
        </div>
    </div>
</section>

<!-- FUNDED BY -->
<section class="funded-by-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-4 mb-md-0 text-center text-md-start">
                <h3 class="funded-title">Funded by</h3>
            </div>
            <div class="col-md-8">
                <div class="partners-grid text-md-start">
                    <div class="partner-logo">
                        <img src="{{ asset('images/british-council.png') }}" alt="British Council">
                    </div>
                    <div class="partner-logo">
                        <img src="{{ asset('images/telu-logo.png') }}" alt="Telkom University">
                    </div>
                    <div class="partner-logo">
                        <img src="{{ asset('images/logo-lancester-u.png') }}" alt="University of Lancester">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection