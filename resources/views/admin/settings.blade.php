@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('content')
    
    <div class="mb-5">
        <h2 class="fw-bold mb-1 brand-font">Pengaturan Akun</h2>
        <p class="text-secondary mb-0">Kelola profil dan keamanan akun admin Anda.</p>
    </div>

    <div class="card border-black rounded-4 shadow-sm" style="max-width: 800px;">
        <div class="card-body p-4 p-lg-5">
            
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <h5 class="fw-bold text-dark border-bottom border-secondary pb-2 mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge"></i> Profil Admin
                    </h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control form-control-lg border-secondary" 
                               value="{{ Auth::user()->name }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Alamat Email</label>
                        <input type="email" class="form-control form-control-lg bg-light border-secondary text-muted" 
                               value="{{ Auth::user()->email }}" disabled>
                        <div class="form-text mt-2 text-secondary">
                            <i class="bi bi-lock-fill me-1"></i> Email tidak dapat diubah demi keamanan.
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h5 class="fw-bold text-dark border-bottom border-secondary pb-2 mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock"></i> Keamanan
                    </h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Password Baru</label>
                            <input type="password" name="password" class="form-control form-control-lg border-secondary" 
                                   placeholder="Biarkan kosong jika tidak diubah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary ls-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg border-secondary" 
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-3 border-top border-light">
                    <button type="submit" class="btn btn-dark btn-lg px-5 rounded-3 fw-bold shadow-sm">
                        Simpan Perubahan <i class="bi bi-check-lg ms-2"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection