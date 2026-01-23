<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Survei Inklusi</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #000000;
            --bg-body: #ffffff;
            --surface: #ffffff;
            --border: #e2e2e2;
            --radius: 12px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 900px; /* Diperlebar sedikit agar muat */
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
        }

        .brand-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
        }

        .section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .form-control, .form-select {
            background-color: #fcfcfc;
            border: 1px solid #d1d1d1;
            padding: 12px 16px;
            border-radius: var(--radius);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05);
        }

        .btn-black {
            background: #000; color: #fff;
            padding: 14px; border-radius: 50px;
            font-weight: 600; width: 100%; border: none;
            transition: all 0.3s;
        }
        .btn-black:hover { background: #333; transform: translateY(-2px); }

        .auth-link { color: #000; font-weight: 600; text-decoration: none; }
        .auth-link:hover { text-decoration: underline; }
        
        /* Styling khusus Radio Button agar rapi */
        .form-check-input:checked {
            background-color: #000;
            border-color: #000;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="auth-card">
        <div class="text-center mb-5">
            <h3 class="brand-title">Buat Akun Baru</h3>
            <p class="text-secondary small">Lengkapi data diri dan institusi untuk memulai survei</p>
        </div>

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-5"> <div class="col-md-6">
                    <div class="section-title">Informasi Akun</div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Naratama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@kampus.ac.id" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="section-title">Identitas Kampus</div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Perguruan Tinggi</label>
                        <input type="text" name="university_name" class="form-control" placeholder="Contoh: Telkom University" required>
                    </div>

                    <div class="mb-3">
                        <label for="university_logo" class="form-label">Logo Kampus</label>
                        <input type="file" 
                            class="form-control @error('university_logo') is-invalid @enderror" 
                            id="university_logo" 
                            name="university_logo" 
                            accept="image/*">
                        @error('university_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted small">Format: JPG, PNG, JPEG. Maks: 2MB.</div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Jenis PT</label>
                            <select name="university_type" class="form-select" required>
                                <option value="" disabled selected>Pilih...</option>
                                <option value="PTN">PTN</option>
                                <option value="PTS">PTS</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Kategori</label>
                            <select name="university_category" class="form-select" required>
                                <option value="" disabled selected>Pilih...</option>
                                <option value="Universitas">Universitas</option>
                                <option value="Institut">Institut</option>
                                <option value="Politeknik">Politeknik</option>
                                <option value="Sekolah Tinggi">Sekolah Tinggi</option>
                                <option value="Akademi">Akademi</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label small fw-bold text-secondary mb-2">
                            Perguruan tinggi memiliki Program Studi Pendidikan Luar Biasa (PLB) atau Pendidikan Khusus?
                        </label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_disability_study_program" id="plb_yes" value="1" required>
                                <label class="form-check-label small" for="plb_yes">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_disability_study_program" id="plb_no" value="0" required>
                                <label class="form-check-label small" for="plb_no">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">No. Telepon / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-secondary border-end-0" style="border-color: #d1d1d1;">+62</span>
                            <input type="number" name="phone_number" class="form-control border-start-0" placeholder="81234567890" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn btn-black mb-3">Daftar Sekarang</button>
                <div class="text-center small text-secondary">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="auth-link">Masuk di sini</a>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>