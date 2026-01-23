@extends('landing.layouts.app')

@section('title', $news->title)

@push('styles')
<style>
    .article-header {
        padding: 3rem 0 2rem;
        border-bottom: 2px solid var(--black);
    }

    .back-link {
        color: var(--gray-700);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
        font-weight: 500;
    }

    .back-link:hover {
        color: var(--black);
    }

    .article-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .article-meta {
        display: flex;
        gap: 2rem;
        color: var(--gray-700);
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .article-meta i {
        margin-right: 0.5rem;
    }

    .article-featured-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border: 2px solid var(--black);
        margin: 2rem 0;
    }

    .article-content {
        padding: 3rem 0;
        max-width: 800px;
        margin: 0 auto;
    }

    .article-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 1.5rem;
        color: var(--gray-900);
    }

    .article-link-box {
        border: 2px solid var(--black);
        padding: 2rem;
        margin: 2rem 0;
        background: var(--gray-100);
    }

    .article-link-box h5 {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .article-link-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--black);
        color: var(--white);
        padding: 1rem 2rem;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .article-link-btn:hover {
        background: var(--white);
        color: var(--black);
        border: 2px solid var(--black);
    }

    .article-footer {
        border-top: 2px solid var(--gray-200);
        padding-top: 3rem;
        margin-top: 3rem;
        text-align: center;
    }

    @media (max-width: 768px) {
        .article-title {
            font-size: 1.75rem;
        }

        .article-content p {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')

<div class="container">
    
    <!-- HEADER -->
    <div class="article-header">
        <a href="{{ route('landing.news') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Berita
        </a>
        
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

    <!-- CONTENT -->
    <div class="article-content">
        
        <!-- Featured Image -->
        @if($news->featured_image)
            <img src="{{ asset('storage/' . $news->featured_image) }}" 
                 alt="{{ $news->title }}" 
                 class="article-featured-image">
        @endif

        <!-- Content -->
        <div>
            {!! nl2br(e($news->content)) !!}
        </div>

        <!-- Link Section -->
        @if($news->link_url && $news->link_text)
            <div class="article-link-box">
                <h5><i class="bi bi-link-45deg me-2"></i>Referensi Tambahan</h5>
                <p class="text-secondary mb-3" style="font-size: 0.95rem;">
                    Untuk informasi lebih lengkap, Anda dapat mengunjungi sumber eksternal berikut:
                </p>
                <a href="{{ $news->link_url }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="article-link-btn">
                    <i class="bi bi-play-circle-fill"></i>
                    {{ $news->link_text }}
                    <i class="bi bi-box-arrow-up-right ms-2"></i>
                </a>
            </div>
        @endif

        <!-- Footer -->
        <div class="article-footer">
            <p class="text-secondary mb-3">Bagikan artikel ini:</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('landing.news-detail', $news->slug)) }}" 
                   target="_blank"
                   class="btn btn-outline-custom">
                    <i class="bi bi-facebook"></i> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('landing.news-detail', $news->slug)) }}&text={{ urlencode($news->title) }}" 
                   target="_blank"
                   class="btn btn-outline-custom">
                    <i class="bi bi-twitter"></i> Twitter
                </a>
                <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . route('landing.news-detail', $news->slug)) }}" 
                   target="_blank"
                   class="btn btn-outline-custom">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>

        <!-- Back to News List -->
        <div class="text-center mt-5">
            <a href="{{ route('landing.news') }}" class="btn-outline-custom">
                <i class="bi bi-arrow-left me-2"></i> Lihat Berita Lainnya
            </a>
        </div>
    </div>
</div>

@endsection