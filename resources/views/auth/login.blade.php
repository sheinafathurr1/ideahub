<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Survei Inklusi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #fff; color: #000; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { width: 100%; max-width: 400px; padding: 40px; border: 1px solid #e2e2e2; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); }
        .form-control { background: #fcfcfc; border: 1px solid #ccc; padding: 12px; border-radius: 8px; }
        .form-control:focus { border-color: #000; box-shadow: 0 0 0 3px rgba(0,0,0,0.05); }
        .btn-black { background: #000; color: #fff; padding: 12px; border-radius: 50px; width: 100%; border: none; font-weight: 600; transition: 0.3s; }
        .btn-black:hover { background: #333; transform: translateY(-2px); }
        a { color: #000; font-weight: 600; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Selamat Datang</h3>
            <p class="text-secondary small">Masuk untuk melanjutkan survei</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@kampus.ac.id" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" style="border-right:0" required>
                    <span class="input-group-text bg-transparent border-start-0" style="border-color:#ccc"><i class="bi bi-eye-slash" id="togglePassword" style="cursor:pointer"></i></span>
                </div>
            </div>
            <button type="submit" class="btn btn-black mb-3">Masuk</button>
            <div class="text-center small text-secondary">Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a></div>
        </form>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const p = document.getElementById('password');
            p.type = p.type === 'password' ? 'text' : 'password';
            this.classList.toggle('bi-eye'); this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>