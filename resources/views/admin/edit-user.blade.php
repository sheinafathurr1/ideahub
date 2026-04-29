@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.users') }}" class="btn btn-outline-dark rounded-circle p-2" style="width: 40px; height: 40px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-0 brand-font">Edit Pengguna</h3>
            <p class="text-secondary mb-0 small">Perbarui data institusi, logo, atau reset password partisipan.</p>
        </div>
    </div>

    <div class="card border-black rounded-4 shadow-sm">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Nama Lengkap / PIC</label>
                        <input type="text" name="name" class="form-control py-2 border-secondary" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Email Login</label>
                        <input type="email" name="email" class="form-control py-2 border-secondary" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12 mt-4">
                        <hr class="text-secondary opacity-25">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Nama Institusi / Kampus</label>
                        <input type="text" name="university_name" class="form-control py-2 border-secondary" value="{{ old('university_name', $user->university_name) }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Logo Kampus</label><br>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            @if($user->university_logo)
                                <img src="{{ asset('storage/' . $user->university_logo) }}" alt="Logo Saat Ini" class="img-thumbnail rounded-3" style="max-height: 80px; width: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 80px; height: 80px;">
                                    <i class="bi bi-image text-secondary fs-3"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" name="university_logo" class="form-control py-2 border-secondary" accept="image/*">
                                <div class="form-text text-muted small">Abaikan jika tidak ingin mengganti logo. Format: JPG/PNG, Maks: 2MB.</div>
                            </div>
                        </div>
                        @error('university_logo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Jenis PT</label>
                        <select name="university_type" class="form-select py-2 border-secondary" required>
                            <option value="PTN" {{ old('university_type', $user->university_type) == 'PTN' ? 'selected' : '' }}>PTN</option>
                            <option value="PTS" {{ old('university_type', $user->university_type) == 'PTS' ? 'selected' : '' }}>PTS</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase">Kategori Institusi</label>
                        <select name="university_category" class="form-select py-2 border-secondary" required>
                            <option value="Universitas" {{ old('university_category', $user->university_category) == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                            <option value="Institut" {{ old('university_category', $user->university_category) == 'Institut' ? 'selected' : '' }}>Institut</option>
                            <option value="Politeknik" {{ old('university_category', $user->university_category) == 'Politeknik' ? 'selected' : '' }}>Politeknik</option>
                            <option value="Sekolah Tinggi" {{ old('university_category', $user->university_category) == 'Sekolah Tinggi' ? 'selected' : '' }}>Sekolah Tinggi</option>
                            <option value="Akademi" {{ old('university_category', $user->university_category) == 'Akademi' ? 'selected' : '' }}>Akademi</option>
                            <option value="Lainnya" {{ old('university_category', $user->university_category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="bg-light p-3 border rounded-3">
                            <label class="form-label small fw-bold text-danger text-uppercase mb-1"><i class="bi bi-key"></i> Reset Password (Opsional)</label>
                            <p class="small text-secondary mb-2">Isi kolom di bawah <b>hanya</b> jika Anda ingin mereset password akun ini. Kosongkan jika tidak ingin mengubahnya.</p>
                            <input type="password" name="password" class="form-control border-secondary bg-white" placeholder="Ketik password baru minimal 8 karakter...">
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-dark px-5 py-2 fw-bold rounded-pill">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection