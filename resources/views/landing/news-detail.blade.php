@extends('landing.layouts.app')

@section('title', $news->title)

@push('styles')
<style>
    .article-header {
        padding: 3rem 0 2rem;
    }

    .back-link {
        color: var(--gray-700);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .article-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.2;
        animation: fadeInUp 0.8s ease;
    }

    .article-meta {
        display: flex;
        gap: 2rem;
        color: var(--gray-700);
        font-size: 0.95rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .article-meta i {
        margin-right: 0.5rem;
    }

    .article-featured-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        margin: 2rem 0;
        transition: transform 0.3s ease;
    }

    .article-content {
        padding: 2rem 0;
        max-width: 800px;
        margin: 0 auto;
    }

    .article-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 1.5rem;
        color: var(--gray-900);
    }

    .article-link-section {
        margin: 2rem 0;
        padding: 1.5rem 0;
        border-top: 1px solid var(--gray-200);
        border-bottom: 1px solid var(--gray-200);
    }

    .article-link-section h5 {
        font-size: 1rem;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }

    .article-link-section a {
        color: var(--black);
        text-decoration: underline;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .article-link-section a:hover {
        color: var(--gray-700);
    }

    .article-footer {
        padding-top: 2rem;
        margin-top: 2rem;
        text-align: center;
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
        .article-header {
            padding: 2.5rem 0 1.75rem;
        }

        .article-title {
            font-size: 2rem;
        }

        .article-content p {
            font-size: 1.05rem;
        }
    }

    @media (max-width: 768px) {
        .article-header {
            padding: 2rem 0 1.5rem;
        }

        .back-link {
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .article-title {
            font-size: 1.75rem;
        }

        .article-meta {
            gap: 1.5rem;
            font-size: 0.9rem;
        }

        .article-featured-image {
            max-height: 400px;
            margin: 1.5rem 0;
        }

        .article-content {
            padding: 1.5rem 0;
        }

        .article-content p {
            font-size: 1rem;
            line-height: 1.7;
        }

        .article-link-section {
            margin: 1.5rem 0;
            padding: 1.25rem 0;
        }

        .article-link-section h5 {
            font-size: 0.95rem;
        }

        .article-link-section a {
            font-size: 0.9rem;
        }

        .article-footer {
            padding-top: 1.5rem;
            margin-top: 1.5rem;
        }

        .btn-outline-custom {
            font-size: 0.9rem;
            padding: 0.6rem 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .article-title {
            font-size: 1.5rem;
        }

        .article-meta {
            gap: 1rem;
            font-size: 0.85rem;
            flex-direction: column;
        }

        .article-featured-image {
            max-height: 300px;
        }

        .article-content p {
            font-size: 0.95rem;
        }

        .article-footer .d-flex {
            flex-direction: column;
            gap: 0.75rem !important;
        }

        .article-footer .btn-outline-custom {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<div class="container">
    
    <div class="article-header">
        <h1 class="article-title">{{ $news->title }}</h1>
        
        <div class="article-meta">
            <span>
                <i class="bi bi-calendar3"></i>
                {{ $news->published_at->format('d M Y') }}
            </span>
            <span>
                <i class="bi bi-person-fill"></i>
                {{ $news->author->name }}
            </span>
        </div>
    </div>

    <div class="article-content">
        
        @if($news->featured_image)
            <img src="{{ asset('storage/' . $news->featured_image) }}" 
                 alt="{{ $news->title }}" 
                 class="article-featured-image">
        @endif

        <div>
            {!! nl2br(e($news->content)) !!}
        </div>

        @if($news->link_url && $news->link_text)
            <div class="article-link-section">
                <h5>Referensi Tambahan</h5>
                <p class="text-secondary mb-2" style="font-size: 0.95rem;">
                    Untuk informasi lebih lengkap, kunjungi:
                </p>
                <a href="{{ $news->link_url }}" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    {{ $news->link_text }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
            </div>
        @endif

        <div class="text-center mt-4 mt-md-5">
            <a href="{{ route('landing.news') }}" class="btn-outline-custom">
                <i class="bi bi-arrow-left me-2"></i> Lihat Berita Lainnya
            </a>
        </div>
    </div>
</div>

@endsection