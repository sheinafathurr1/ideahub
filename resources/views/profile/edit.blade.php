<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Profil - IdeaHub</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --primary: #000000; --bg-body: #f8f9fa; --surface: #ffffff; --border: #e2e2e2; --radius: 12px; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--primary); padding: 40px 20px; min-height: 100vh;}
        .profile-card { width: 100%; max-width: 900px; margin: 0 auto; background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); }
        .section-title { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #666; font-weight: 700; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .form-control, .form-select { background-color: #fcfcfc; border: 1px solid #d1d1d1; padding: 12px 16px; border-radius: var(--radius); }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,0,0,0.05); }
        .btn-black { background: #000; color: #fff; padding: 14px; border-radius: 50px; font-weight: 600; border: none; transition: all 0.3s; }
        .btn-black:hover { background: #333; transform: translateY(-2px); }
        .form-check-input:checked { background-color: #000; border-color: #000; }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-card">
        
        <div class="d-flex align-items-center justify-content-between mb-5 border-bottom pb-3">
            <div>
                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;" class="mb-1">Ubah Profil</h3>
                <p class="text-secondary small mb-0">Perbarui data diri dan institusi Anda</p>
            </div>
            <a href="{{ route('dashboard.index') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">Kembali</a>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="row g-5">
                <div class="col-md-6">
                    <div class="section-title">Informasi Akun</div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">No. Telepon / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-secondary border-end-0">+62</span>
                            <input type="number" name="phone_number" class="form-control border-start-0" value="{{ old('phone_number', $user->phone_number) }}" required>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border">
                        <label class="form-label small fw-bold text-secondary mb-1">Ganti Password</label>
                        <p class="small text-muted mb-2">Kosongkan jika tidak ingin mengubah password.</p>
                        
                        <input type="password" name="password" class="form-control mb-2" placeholder="Password Baru">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password Baru">
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="section-title">Identitas Kampus</div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Perguruan Tinggi</label>
                        <input type="text" name="university_name" class="form-control" value="{{ old('university_name', $user->university_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Logo Saat Ini</label><br>
                        @if($user->university_logo)
                            <img src="{{ asset('storage/' . $user->university_logo) }}" alt="Logo" class="img-thumbnail mb-2" style="max-height: 80px;">
                        @else
                            <span class="badge bg-secondary mb-2">Belum ada logo</span>
                        @endif
                        <input type="file" class="form-control" name="university_logo" accept="image/*">
                        <div class="form-text text-muted small">Abaikan jika tidak ingin mengganti logo.</div>
                        @error('university_logo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Jenis PT</label>
                            <select name="university_type" class="form-select" required>
                                <option value="PTN" {{ old('university_type', $user->university_type) == 'PTN' ? 'selected' : '' }}>PTN</option>
                                <option value="PTS" {{ old('university_type', $user->university_type) == 'PTS' ? 'selected' : '' }}>PTS</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Kategori</label>
                            <select name="university_category" class="form-select" required>
                                <option value="Universitas" {{ old('university_category', $user->university_category) == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                                <option value="Institut" {{ old('university_category', $user->university_category) == 'Institut' ? 'selected' : '' }}>Institut</option>
                                <option value="Politeknik" {{ old('university_category', $user->university_category) == 'Politeknik' ? 'selected' : '' }}>Politeknik</option>
                                <option value="Sekolah Tinggi" {{ old('university_category', $user->university_category) == 'Sekolah Tinggi' ? 'selected' : '' }}>Sekolah Tinggi</option>
                                <option value="Akademi" {{ old('university_category', $user->university_category) == 'Akademi' ? 'selected' : '' }}>Akademi</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label small fw-bold text-secondary mb-2">
                            Memiliki Prodi Pendidikan Luar Biasa (PLB)?
                        </label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_disability_study_program" id="plb_yes" value="1" {{ old('has_disability_study_program', $user->has_disability_study_program) == 1 ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="plb_yes">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_disability_study_program" id="plb_no" value="0" {{ old('has_disability_study_program', $user->has_disability_study_program) == 0 ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="plb_no">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end border-top pt-4">
                <button type="submit" class="btn btn-black px-5 py-3">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            iconColor: '#000',
            confirmButtonColor: '#000'
        });
    @endif
</script>

</body>
</html>