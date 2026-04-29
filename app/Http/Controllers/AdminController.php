<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

   
    /**
     * FUNGSI IMPORT CSV (SUPER AMAN + MENDUKUNG PARAGRAF/ENTER DI DALAM ESAI)
     */
    public function importReport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        
        // 1. AUTO-DETECT DELIMITER DENGAN AMAN
        $handle = fopen($path, 'r');
        $firstLine = fgets($handle);
        if (!$firstLine) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'File CSV kosong.']);
        }
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($handle); // Kembalikan pointer baca ke awal file

        // 2. BACA CSV DENGAN fgetcsv (Kebal terhadap ENTER di dalam teks/esai)
        $data = [];
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $data[] = $row;
        }
        fclose($handle);

        if (count($data) < 2) {
            return back()->withErrors(['csv_file' => 'Format CSV tidak sesuai.']);
        }

        // 3. BERSIHKAN HEADER
        $headers = array_map('trim', $data[0]);
        $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);
        $headerMap = array_flip($headers);

        $questions = \App\Models\Question::with('options')->get();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Lewati baris metadata bawaan Qualtrics
            $startIndex = 1;
            if (isset($data[1][0]) && (str_contains(strtolower($data[1][0]), 'progress') || str_contains(strtolower($data[1][0]), 'startdate'))) {
                $startIndex = 2;
                if (isset($data[2][0]) && str_contains($data[2][0], '{"ImportId"')) {
                    $startIndex = 3;
                }
            }

            for ($i = $startIndex; $i < count($data); $i++) {
                $row = $data[$i];
                if (count($row) < 3) continue; 

                // A. MAPPING IDENTITAS DENGAN PELINDUNG KARAKTER
                $nameRaw = $this->getCellValue($row, $headerMap, ['Q0a', 'Nama Lengkap']) ?? 'User Import ' . $i;
                $name = substr($nameRaw, 0, 200);

                $univNameRaw = $this->getCellValue($row, $headerMap, ['Q1', 'Nama Instansi / Kampus', 'Nama Perguruan Tinggi']) ?? 'Kampus Unknown';
                $univName = substr($univNameRaw, 0, 200);
                if (empty(trim($univName))) $univName = 'Kampus Unknown';
                
                $univTypeRaw = strtoupper($this->getCellValue($row, $headerMap, ['Q2', 'Jenis PT']) ?? 'PTN');
                $univType = in_array($univTypeRaw, ['PTN', 'PTS']) ? $univTypeRaw : 'PTN';
                
                $univCatRaw = $this->getCellValue($row, $headerMap, ['Q4', 'Kategori']) ?? 'Universitas';
                if (strlen($univCatRaw) > 100) {
                    $univCat = 'Lainnya';
                } else {
                    $univCat = substr($univCatRaw, 0, 100);
                }

                // B. HANDLE EMAIL DENGAN PELINDUNG KARAKTER (DIPERPENDEK)
                $email = $this->getCellValue($row, $headerMap, ['Email', 'email']);
                if (empty($email) || $email === '-') {
                    // Bersihkan spasi dan karakter khusus dari nama kampus
                    $cleanUnivName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($univName));
                    if (empty($cleanUnivName)) $cleanUnivName = 'kampus';
                    
                    // Ambil maksimal 10 huruf pertama saja + nomor baris + @dummy.com
                    // Contoh hasil: universita_5@dummy.com
                    $email = substr($cleanUnivName, 0, 10) . '_' . $i . '@dummy.com';
                }

                // C. BUAT USER
                $user = \App\Models\User::where('email', $email)->first();
                if (!$user) {
                    $user = \App\Models\User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                        'phone_number' => '0000000000',
                        'university_name' => $univName,
                        'university_type' => $univType,
                        'university_category' => $univCat,
                        'has_disability_study_program' => 0,
                        'role' => 'user'
                    ]);
                }

                if (!$user || !$user->id) {
                    throw new \Exception("Gagal membuat akun User pada baris ke-{$i}.");
                }

                // D. BUAT SUBMISI
                $submission = \App\Models\Submission::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'status' => 'accepted', 
                        'submitted_at' => now(),
                        'current_step' => 10
                    ]
                );

                // E. MAPPING JAWABAN SURVEI
                foreach ($questions as $q) {
                    $qCode = strtoupper($q->code ?? ''); 
                    if (empty($qCode)) continue;

                    if ($q->type == 'matrix' && $q->options->count() > 0) {
                        $matrixValues = [];
                        $optIndex = 1;
                        
                        foreach ($q->options as $opt) {
                            $possibleHeaders = [
                                $qCode . '_' . $optIndex,
                                $qCode . '_' . $optIndex . '_TEXT',
                                $q->question . ' - ' . trim($opt->option_label)
                            ];

                            $val = $this->getCellValue($row, $headerMap, $possibleHeaders);
                            if ($val !== null && $val !== '') {
                                $matrixValues[trim($opt->option_label)] = $val;
                            }
                            $optIndex++;
                        }

                        if (!empty($matrixValues)) {
                            \App\Models\SubmissionValue::updateOrCreate(
                                ['submission_id' => $submission->id, 'question_id' => $q->id],
                                ['value' => json_encode($matrixValues)]
                            );
                        }
                    } 
                    else {
                        $possibleHeaders = [
                            $qCode,
                            $qCode . '_1',
                            $qCode . '_TEXT',
                            str_replace(["\r", "\n", "\t"], " ", $q->question)
                        ];

                        $val = $this->getCellValue($row, $headerMap, $possibleHeaders);

                        if ($val !== null && $val !== '') {
                            if ($q->type == 'checkbox') {
                                $arrVal = array_map('trim', explode(',', $val));
                                $val = json_encode($arrVal);
                            }

                            \App\Models\SubmissionValue::updateOrCreate(
                                ['submission_id' => $submission->id, 'question_id' => $q->id],
                                ['value' => $val]
                            );
                        }
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Data CSV berhasil diimport dan dipetakan secara otomatis!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['csv_file' => 'Gagal Import: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper Function Aman untuk mencari nilai dari Header CSV
     */
    private function getCellValue($row, $headerMap, $possibleHeaders) {
        foreach ($possibleHeaders as $header) {
            if (isset($headerMap[$header]) && isset($row[$headerMap[$header]])) {
                return trim($row[$headerMap[$header]]);
            }
        }
        return null;
    }

    // Menampilkan halaman form edit user
    public function editUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    // Memproses update data user
    // Memproses update data user
    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'university_name' => 'required|string|max:255',
            'university_type' => 'required|in:PTN,PTS',
            'university_category' => 'required|string',
            'password' => 'nullable|string|min:8',
            // TAMBAHKAN VALIDASI LOGO
            'university_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ];

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->university_name = $validated['university_name'];
        $user->university_type = $validated['university_type'];
        $user->university_category = $validated['university_category'];

        // Jika admin mengisi field password, maka password user di-reset
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        // LOGIKA PENYIMPANAN LOGO BARU
        if ($request->hasFile('university_logo')) {
            // Hapus logo lama dari server jika ada
            if ($user->university_logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->university_logo);
            }

            // Simpan logo baru
            $file = $request->file('university_logo');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'Logo_' . \Illuminate\Support\Str::slug($validated['university_name']) . '_' . time() . '.' . $extension;
            
            $user->university_logo = $file->storeAs('university_logos', $fileName, 'public');
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diperbarui!');
    }
}