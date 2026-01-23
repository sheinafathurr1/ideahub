@extends('landing.layouts.app')

@section('title', 'Berita & Insight')

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

    .news-grid {
        padding: 3rem 0;
    }

    .news-card {
        border: 2px solid var(--black);
        overflow: hidden;
        height: 100%;
        background: var(--white);
        transition: all 0.3s;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 8px 8px 0 var(--black);
        color: inherit;
    }

    .news-card-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .news-card-placeholder {
        width: 100%;
        height: 250px;
        background: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .news-card-body {
        padding: 2rem;
    }

    .news-card-date {
        font-size: 0.85rem;
        color: var(--gray-700);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .news-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--black);
        line-height: 1.4;
    }

    .news-card-excerpt {
        color: var(--gray-700);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .news-card-link {
        color: var(--black);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination {
        margin-top: 3rem;
    }

    .pagination .page-link {
        border: 2px solid var(--black);
        color: var(--black);
        font-weight: 600;
        margin: 0 0.25rem;
        padding: 0.75rem 1.25rem;
    }

    .pagination .page-link:hover {
        background: var(--black);
        color: var(--white);
    }

    .pagination .page-item.active .page-link {
        background: var(--black);
        border-color: var(--black);
    }

    .no-news {
        text-align: center;
        padding: 5rem 2rem;
    }

    .no-news i {
        font-size: 5rem;
        color: var(--gray-300);
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

<!-- HEADER -->
<section class="page-header">
    <div class="container">
        <h1>Berita & Insight</h1>
        <p style="font-size: 1.1rem; color: rgba(255,255,255,0.85);">
            Update terbaru seputar inklusi disabilitas di perguruan tinggi Indonesia
        </p>
    </div>
</section>

<!-- NEWS GRID -->
<section class="news-grid">
    <div class="container">
        
        @if($news->total() > 0)
            <div class="row g-4">
                @foreach($news as $item)
                    <div class="col-md-4">
                        <a href="{{ route('landing.news-detail', $item->slug) }}" class="news-card">
                            @if($item->featured_image)
                                <img src="{{ asset('storage/' . $item->featured_image) }}" 
                                     alt="{{ $item->title }}" 
                                     class="news-card-image">
                            @else
                                <div class="news-card-placeholder">
                                    <i class="bi bi-newspaper" style="font-size: 3rem; color: var(--gray-700);"></i>
                                </div>
                            @endif
                            
                            <div class="news-card-body">
                                <div class="news-card-date">
                                    <i class="bi bi-calendar3"></i> 
                                    {{ $item->published_at->format('d M Y') }}
                                </div>
                                
                                <h3 class="news-card-title">{{ $item->title }}</h3>
                                
                                @if($item->excerpt)
                                    <p class="news-card-excerpt">
                                        {{ Str::limit($item->excerpt, 120) }}
                                    </p>
                                @endif
                                
                                <span class="news-card-link">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $news->links() }}
                </div>
            @endif
        @else
            <div class="no-news">
                <i class="bi bi-newspaper"></i>
                <h4>Belum ada berita tersedia</h4>
                <p class="text-secondary">Berita akan segera ditambahkan</p>
            </div>
        @endif
    </div>
</section>

@endsection