<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // STATISTIK UTAMA
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_submissions' => Submission::count(),
            'pending' => Submission::where('status', 'submitted')->count(),
            'accepted' => Submission::where('status', 'accepted')->count(),
            'rejected' => Submission::where('status', 'rejected')->count(),
        ];

        // DAFTAR SUBMISI MENUNGGU REVIEW (Prioritas)
        $pendingSubmissions = Submission::with('user')
                                ->where('status', 'submitted')
                                ->orderBy('submitted_at', 'asc')
                                ->get();

        // RIWAYAT SUBMISI (Accepted/Rejected)
        $historySubmissions = Submission::with('user')
                                ->whereIn('status', ['accepted', 'rejected'])
                                ->orderBy('updated_at', 'desc')
                                ->take(10)
                                ->get();

        return view('admin.dashboard', compact('stats', 'pendingSubmissions', 'historySubmissions'));
    }

    public function show($id)
    {
        $submission = Submission::with(['user', 'values'])->findOrFail($id);
        
        // Ambil struktur pertanyaan (sama seperti view user)
        $questions = Question::with('options')->orderBy('order_position')->get();
        $chunks = $questions->groupBy('dimension');
        if($chunks->count() <= 1) $chunks = $questions->chunk(10);
        
        // Mapping Jawaban
        $answers = $submission->values->pluck('value', 'question_id')->toArray();

        return view('admin.show', compact('submission', 'chunks', 'answers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'admin_feedback' => 'required_if:status,rejected', // Wajib isi jika ditolak
        ]);

        $submission = Submission::findOrFail($id);
        
        $submission->update([
            'status' => $request->status,
            'admin_feedback' => $request->admin_feedback,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Status submisi berhasil diperbarui.');
    }

    // ... method index, show, updateStatus sudah ada sebelumnya ...

    // --- FITUR BARU: MENU USERS ---
    public function users()
    {
        // Ambil semua user (role user), urutkan dari yang terbaru
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users', compact('users'));
    }


    // --- FITUR BARU: MENU SETTINGS ---
    public function settings()
    {
        return view('admin.settings');
    }

    public function reports()
    {
        $reportData = [
            'total' => Submission::count(),
            'accepted' => Submission::where('status', 'accepted')->count(),
            'rejected' => Submission::where('status', 'rejected')->count(),
            'submitted' => Submission::where('status', 'submitted')->count(),
            'draft' => Submission::where('status', 'draft')->count(),
        ];
        
        return view('admin.reports', compact('reportData'));
    }

    /**
     * FUNGSI EXPORT CSV (FITUR UTAMA)
     * - Header Pertanyaan Lengkap
     * - Pemisahan Kolom Matrix (Q6a)
     */
    public function exportReport(Request $request)
    {
        // 1. Ambil Filter Status
        $status = $request->query('status');
        $fileName = 'DATA_SURVEI_' . ($status ? strtoupper($status) : 'ALL') . '_' . date('Y-m-d_H-i') . '.csv';

        // 2. Query Data Submisi
        $query = Submission::with(['user', 'values']);

        if ($status && in_array($status, ['accepted', 'rejected', 'submitted', 'draft'])) {
            $query->where('status', $status);
        } else {
            $query->where('status', '!=', 'draft'); 
        }

        $submissions = $query->orderBy('submitted_at', 'desc')->get();

        // 3. Ambil Pertanyaan + Opsi (PENTING: Eager load options)
        $questions = Question::with('options')->orderBy('order_position', 'asc')->get();

        // 4. Siapkan Header CSV Statis
        $headers = [
            'No',
            'Status Submisi',
            'Tanggal Kirim',
            'Waktu Kirim',
            'Nama Lengkap',
            'Email',
            'No HP',
            'Nama Instansi / Kampus',
            'Jenis PT',
            'Kategori',
        ];

        // 5. Generate Header Dinamis (Pertanyaan)
        foreach ($questions as $q) {
            $cleanQuestion = str_replace(["\r", "\n", "\t"], " ", $q->question);

            // LOGIKA KHUSUS: Jika Matrix (Q6a), pecah header per opsi
            if ($q->type == 'matrix' && $q->options->count() > 0) {
                foreach ($q->options as $opt) {
                    // Header Format: "Pertanyaan - Opsi"
                    // Contoh: "Jumlah Mahasiswa Disabilitas - Low Vision"
                    $headers[] = $cleanQuestion . ' - ' . trim($opt->option_label); 
                }
            } else {
                // Pertanyaan Biasa
                $headers[] = $cleanQuestion;
            }
        }

        // 6. Generate Stream Download
        $callback = function() use ($submissions, $questions, $headers) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM untuk Excel
            fputs($file, "\xEF\xBB\xBF"); 

            fputcsv($file, $headers); // Tulis Header

            $no = 1;
            foreach ($submissions as $sub) {
                $date = $sub->submitted_at ? \Carbon\Carbon::parse($sub->submitted_at)->format('Y-m-d') : '-';
                $time = $sub->submitted_at ? \Carbon\Carbon::parse($sub->submitted_at)->format('H:i') : '-';

                // A. Data Identitas
                $row = [
                    $no++,
                    strtoupper($sub->status),
                    $date,
                    $time,
                    $sub->user->name ?? '-',
                    $sub->user->email ?? '-',
                    $sub->user->phone_number ?? '-',
                    $sub->user->university_name ?? '-',
                    $sub->user->university_type ?? '-',
                    $sub->user->university_category ?? '-',
                ];

                // B. Mapping Jawaban
                $answers = $sub->values->pluck('value', 'question_id')->toArray();

                foreach ($questions as $q) {
                    $val = $answers[$q->id] ?? ''; 

                    // LOGIKA KHUSUS: Pecah Jawaban Matrix ke Kolom Terpisah
                    if ($q->type == 'matrix' && $q->options->count() > 0) {
                        
                        // Decode Jawaban JSON Matrix
                        // Format biasanya: {"Low Vision": "2", "Buta Warna": "0"}
                        $decoded = json_decode($val, true);
                        if (!is_array($decoded)) $decoded = [];

                        // Loop Opsi untuk memastikan urutan kolom sesuai Header
                        foreach ($q->options as $opt) {
                            // Ambil nilai berdasarkan Label Opsi
                            // Jika ada key yang cocok, ambil nilainya. Jika tidak, kosong.
                            $matrixVal = $decoded[$opt->option_label] ?? '';
                            $row[] = $matrixVal; // Masukkan ke sel terpisah
                        }

                    } else {
                        // LOGIKA BIASA (Non-Matrix)
                        if ($this->isJson($val)) {
                            $decoded = json_decode($val, true);
                            
                            if (is_array($decoded)) {
                                // Kasus Checkbox (List Pilihan)
                                // Contoh: "Pilihan A, Pilihan B"
                                $val = implode(", ", $decoded);
                            }
                        } else {
                            // Kasus Teks Biasa
                            $val = str_replace(["\r", "\n", "\t"], " ", $val);
                        }
                        $row[] = $val;
                    }
                }

                fputcsv($file, $row); // Tulis Baris Data
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    // Helper: Cek JSON valid
    private function isJson($string) {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }

    public function history(Request $request)
    {
        // Query dasar: Ambil yang statusnya 'accepted' atau 'rejected'
        $query = Submission::with('user')->whereIn('status', ['accepted', 'rejected']);

        // 1. Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 2. Fitur Pencarian (Nama User / Kampus)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('university_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang paling baru diupdate (diproses)
        $submissions = $query->orderBy('updated_at', 'desc')->paginate(10);

        // Append query string agar pagination tidak mereset filter
        $submissions->appends($request->all());

        return view('admin.history', compact('submissions'));
    }
}