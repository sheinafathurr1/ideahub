@extends('admin.layouts.app')

@section('title', 'Manage News')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-5">
    <div>
        <h2 class="fw-bold mb-1 brand-font">Manage News</h2>
        <p class="text-secondary mb-0">Kelola berita yang ditampilkan di landing page</p>
    </div>
    <a href="{{ route('admin.news.create') }}" class="btn btn-dark px-4 py-2 rounded-2 fw-bold">
        <i class="bi bi-plus-lg me-2"></i>Tambah Berita
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 35%">Judul Berita</th>
                    <th style="width: 15%">Author</th>
                    <th style="width: 15%">Tanggal Dibuat</th>
                    <th style="width: 10%">Status</th>
                    <th class="text-end pe-4" style="width: 20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $index => $item)
                    <tr>
                        <td class="ps-4 fw-medium text-secondary">
                            {{ $news->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-1">{{ $item->title }}</div>
                            @if($item->excerpt)
                                <div class="small text-secondary">{{ Str::limit($item->excerpt, 60) }}</div>
                            @endif
                            @if($item->link_text && $item->link_url)
                                <div class="small mt-1">
                                    <i class="bi bi-link-45deg text-primary"></i>
                                    <span class="text-primary">{{ Str::limit($item->link_text, 40) }}</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-medium">{{ $item->author->name }}</div>
                            <div class="small text-secondary">{{ $item->author->email }}</div>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $item->created_at->format('d M Y') }}</div>
                            <div class="small text-secondary">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            @if($item->status === 'published')
                                <span class="badge bg-black text-white px-3 py-2 rounded-1">
                                    <i class="bi bi-check-circle-fill me-1"></i>Published
                                </span>
                            @else
                                <span class="badge bg-light text-dark border border-secondary px-3 py-2 rounded-1">
                                    <i class="bi bi-pencil-fill me-1"></i>Draft
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.news.edit', $item->id) }}" 
                                   class="btn btn-outline-dark btn-sm px-3 rounded-2">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.news.destroy', $item->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-2">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-secondary opacity-50 mb-2">
                                <i class="bi bi-newspaper fs-1"></i>
                            </div>
                            <div class="fw-medium text-secondary">Belum ada berita yang dibuat.</div>
                            <a href="{{ route('admin.news.create') }}" class="btn btn-dark btn-sm mt-3 px-4 rounded-2">
                                <i class="bi bi-plus-lg me-2"></i>Buat Berita Pertama
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($news->hasPages())
        <div class="p-4 border-top">
            {{ $news->links() }}
        </div>
    @endif
</div>

@endsection
