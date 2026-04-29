@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@section('content')
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1 brand-font">Daftar Pengguna</h2>
            <p class="text-secondary mb-0">Kelola data partisipan survei IdeaHub.</p>
        </div>
        
        <button class="btn btn-dark rounded-pill px-4 d-flex align-items-center gap-2 fw-bold shadow-sm">
            <i class="bi bi-download"></i> Export Data
        </button>
    </div>

    <div class="table-card shadow-sm">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Nama / Email</th>
                    <th>Institusi</th>
                    <th>Tanggal Bergabung</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                            <div class="small text-secondary">{{ $user->email }}</div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $user->university_name ?? '-' }}</div>
                            <span class="badge bg-light text-dark border border-secondary fw-normal">
                                {{ $user->university_type ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="text-secondary small fw-medium">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-dark p-0 border-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-black shadow-sm">
                                    <li><a class="dropdown-item small" href="{{ route('admin.users.edit', $user->id) }}">Edit User</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item small text-danger fw-bold" href="#">Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            <i class="bi bi-person-x fs-1 d-block mb-2 opacity-50"></i>
                            Belum ada user terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $users->links() }}
    </div>

@endsection