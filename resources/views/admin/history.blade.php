@extends('admin.layouts.app')

@section('title', 'Riwayat Submisi')

@section('content')
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold mb-1 brand-font">Riwayat Submisi</h2>
            <p class="text-secondary mb-0">Arsip data survei yang telah selesai diproses.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('admin.history') }}" method="GET" class="d-flex gap-3">
                
                <select name="status" class="form-select border-black w-auto fw-medium" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <div class="input-group">
                    <input type="text" name="search" class="form-control border-black" 
                           placeholder="Cari nama kampus, email, atau user..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-dark" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                @if(request('status') || request('search'))
                    <a href="{{ route('admin.history') }}" class="btn btn-outline-dark d-flex align-items-center" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="table-card">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4">Identitas Kampus</th>
                    <th>User / Kontak</th>
                    <th>Tgl Proses</th>
                    <th>Status Akhir</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $sub->user->university_name ?? '-' }}</div>
                            <div class="small text-secondary">{{ $sub->user->university_type ?? 'Umum' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $sub->user->name }}</div>
                            <div class="small text-secondary">{{ $sub->user->email }}</div>
                        </td>
                        <td>
                            <div class="text-dark">{{ $sub->updated_at->format('d M Y') }}</div>
                            <div class="small text-secondary">{{ $sub->updated_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            @if($sub->status == 'accepted')
                                <span class="badge bg-black text-white px-3 py-2 rounded-1">
                                    <i class="bi bi-check-lg me-1"></i> Accepted
                                </span>
                            @else
                                <span class="badge bg-white text-black border border-black px-3 py-2 rounded-1 text-decoration-line-through">
                                    <i class="bi bi-x-lg me-1"></i> Rejected
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.show', $sub->id) }}" class="btn btn-sm btn-outline-dark rounded-2 px-3 fw-bold">
                                Detail <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <div class="mb-2 opacity-50"><i class="bi bi-inbox fs-1"></i></div>
                            <div class="fw-medium">Tidak ada riwayat submisi ditemukan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $submissions->links() }}
    </div>

@endsection