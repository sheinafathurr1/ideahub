@extends('landing.layouts.app')
@section('title', 'News and Insight')
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
        overflow: hidden;
        height: 100%;
        background: var(--black);
        color: var(--white);
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .news-card-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .news-card-placeholder {
        width: 100%;
        height: 250px;
        background: var(--gray-300);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .news-card-body {
        padding: 2rem;
    }
    .news-card-date {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .news-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--white);
        line-height: 1.4;
    }
    .news-card-excerpt {
        color: rgba(255,255,255,0.8);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    .news-card-link {
        color: var(--white);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.3s ease;
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
        border: 2px solid var(--black);
        background: var(--white);
        color: var(--black);
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
    
    .pagination .page-item.active .page-link {
        background: var(--black);
        color: var(--white);
        border-color: var(--black);
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
    
    .no-news {
        text-align: center;
        padding: 5rem 2rem;
    }
    .no-news i {
        font-size: 5rem;
        color: var(--gray-300);
        margin-bottom: 1rem;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0;
        }
        .page-header h1 {
            font-size: 2rem;
        }
        .page-header p {
            font-size: 1rem;
        }
        .news-grid {
            padding: 2rem 0;
        }
        .news-card-image,
        .news-card-placeholder {
            height: 200px;
        }
        .news-card-body {
            padding: 1.5rem;
        }
        .news-card-title {
            font-size: 1.15rem;
        }
        .news-card-excerpt {
            font-size: 0.9rem;
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
        .page-header p {
            font-size: 0.95rem;
            padding: 0 1rem;
        }
        .news-card-image,
        .news-card-placeholder {
            height: 180px;
        }
        .news-card-body {
            padding: 1.25rem;
        }
        .news-card-title {
            font-size: 1.05rem;
        }
        .no-news {
            padding: 4rem 1.5rem;
        }
        .no-news i {
            font-size: 4rem;
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
<section class="page-header">
    <div class="container">
        <h1>News and Insight</h1>
        <p style="font-size: 1.1rem; color: rgba(255,255,255,0.85);">
            Update terbaru seputar inklusi disabilitas di perguruan tinggi Indonesia
        </p>
    </div>
</section>
<section class="news-grid">
    <div class="container">
        
        @if($news->total() > 0)
            <div class="row g-4">
                @foreach($news as $item)
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ route('landing.news-detail', $item->slug) }}" class="news-card" target="_blank"rel="noopener noreferrer">
                            @if($item->featured_image)
                                <img src="{{ asset('storage/' . $item->featured_image) }}" 
                                     alt="{{ $item->title }}" 
                                     class="news-card-image">
                            @else
                                <div class="news-card-placeholder">
                                    <img src="{{ asset('images/ideahub-logo.png') }}" alt="IdeaHub" style="width: 10rem; object-fit: contain; filter: grayscale(100%) brightness(0) invert(1);">
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
                                    Baca selengkapnya →
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            @if($news->hasPages())
                <div class="pagination-wrapper">
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