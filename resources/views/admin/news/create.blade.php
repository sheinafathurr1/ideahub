@extends('admin.layouts.app')

@section('title', 'Tambah Berita Baru')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.news.index') }}" class="text-decoration-none text-secondary d-inline-flex align-items-center mb-3">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Berita
    </a>
    <h2 class="fw-bold mb-1 brand-font">Tambah Berita Baru</h2>
    <p class="text-secondary mb-0">Isi form di bawah untuk membuat berita baru</p>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-card p-4">
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-4">
            <label for="title" class="form-label fw-bold">
                Judul Berita <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                   id="title" 
                   name="title" 
                   value="{{ old('title') }}"
                   placeholder="Masukkan judul berita"
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Excerpt --}}
        <div class="mb-4">
            <label for="excerpt" class="form-label fw-bold">
                Ringkasan <span class="text-secondary small">(Opsional)</span>
            </label>
            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                      id="excerpt" 
                      name="excerpt" 
                      rows="2"
                      placeholder="Ringkasan singkat berita (max 500 karakter)">{{ old('excerpt') }}</textarea>
            @error('excerpt')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Content --}}
        <div class="mb-4">
            <label for="content" class="form-label fw-bold">
                Konten Berita <span class="text-danger">*</span>
            </label>
            <textarea class="form-control @error('content') is-invalid @enderror" 
                      id="content" 
                      name="content" 
                      rows="10"
                      placeholder="Tulis konten berita lengkap di sini..."
                      required>{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-secondary">Gunakan enter untuk paragraf baru</small>
        </div>

        {{-- Featured Image --}}
        <div class="mb-4">
            <label for="featured_image" class="form-label fw-bold">
                Gambar Utama <span class="text-secondary small">(Opsional)</span>
            </label>
            <input type="file" 
                   class="form-control @error('featured_image') is-invalid @enderror" 
                   id="featured_image" 
                   name="featured_image"
                   accept="image/jpeg,image/png,image/jpg,image/webp">
            @error('featured_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-secondary">Format: JPG, PNG, WEBP. Max 2MB</small>
        </div>

        {{-- Link Section --}}
        <div class="border rounded-3 p-4 mb-4 bg-light">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-link-45deg me-2"></i>Link Tambahan (Opsional)
            </h6>
            <p class="small text-secondary mb-3">
                Tambahkan link dengan text kustom yang akan ditampilkan di berita. 
                Contoh: "Video lengkap mengenai WCAG"
            </p>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="link_text" class="form-label fw-bold">Text Link</label>
                    <input type="text" 
                           class="form-control @error('link_text') is-invalid @enderror" 
                           id="link_text" 
                           name="link_text" 
                           value="{{ old('link_text') }}"
                           placeholder="Contoh: Video lengkap mengenai WCAG">
                    @error('link_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="link_url" class="form-label fw-bold">URL Tujuan</label>
                    <input type="url" 
                           class="form-control @error('link_url') is-invalid @enderror" 
                           id="link_url" 
                           name="link_url" 
                           value="{{ old('link_url') }}"
                           placeholder="https://www.youtube.com/watch?v=...">
                    @error('link_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label class="form-label fw-bold">
                Status Publikasi <span class="text-danger">*</span>
            </label>
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="radio" 
                           name="status" 
                           id="status_draft" 
                           value="draft"
                           {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_draft">
                        <i class="bi bi-pencil-fill me-1"></i> Draft
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" 
                           type="radio" 
                           name="status" 
                           id="status_published" 
                           value="published"
                           {{ old('status') === 'published' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_published">
                        <i class="bi bi-check-circle-fill me-1"></i> Published
                    </label>
                </div>
            </div>
            <small class="text-secondary">Pilih "Published" untuk langsung menampilkan berita di landing page</small>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-3 pt-3 border-top">
            <button type="submit" class="btn btn-dark px-4 py-2 rounded-2 fw-bold">
                <i class="bi bi-save me-2"></i>Simpan Berita
            </button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-2">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
