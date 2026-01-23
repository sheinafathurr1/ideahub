<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Submission;
use App\Models\SubmissionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        $questions = Question::with('options')
                        ->where('is_active', true)
                        ->orderBy('order_position')
                        ->get();

        $chunks = $questions->groupBy('dimension'); 
        if($chunks->count() <= 1) {
            $chunks = $questions->chunk(10); 
        }

        // Ambil submisi aktif (Draft atau Rejected) agar user bisa melanjutkannya
        $submission = Submission::where('user_id', Auth::id())
                        ->whereIn('status', ['draft', 'rejected']) 
                        ->first();
        
        // Jika statusnya sudah submitted/accepted, redirect (opsional)
        // $completed = Submission::where('user_id', Auth::id())->whereIn('status', ['submitted', 'accepted'])->first();
        // if($completed) return redirect()->route('dashboard.index')->with('info', 'Submisi Anda sudah dikirim.');

        $existingAnswers = [];
        if ($submission) {
            $values = SubmissionValue::where('submission_id', $submission->id)->get();
            foreach ($values as $val) {
                // Auto decode JSON
                if ($this->isJson($val->value)) {
                    $existingAnswers[$val->question_id] = json_decode($val->value, true);
                } else {
                    $existingAnswers[$val->question_id] = $val->value;
                }
            }
        }

        return view('survey.index', compact('chunks', 'submission', 'existingAnswers'));
    }

    /**
     * LOGIKA UTAMA: MENANGANI DRAFT DAN SUBMIT
     */
    public function store(Request $request)
    {
        // 1. Cek Tombol Aksi (Draft vs Submit)
        // Default ke 'draft' jika tidak ada input action
        $action = $request->input('action', 'draft'); 

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // 2. Cari Submisi yang sedang aktif (Draft atau Rejected)
            // Kita cari dulu manual agar tidak membuat duplikat jika statusnya 'rejected'
            $submission = Submission::where('user_id', $user->id)
                            ->whereIn('status', ['draft', 'rejected'])
                            ->first();

            // Jika belum ada sama sekali, buat baru dengan status draft
            if (!$submission) {
                $submission = new Submission();
                $submission->user_id = $user->id;
                $submission->status = 'draft';
                $submission->current_step = 1;
                $submission->save();
            }

            // 3. Update Data Header (Step & Status)
            if ($request->has('current_step')) {
                $submission->current_step = $request->current_step;
            }

            // PENTING: Hanya ubah status jadi 'submitted' jika action = 'submit'
            // Jika action = 'draft', biarkan status apa adanya (tetap 'draft' atau 'rejected')
            if ($action === 'submit') {
                $submission->status = 'submitted';
                $submission->submitted_at = now();
            }
            
            $submission->save();

            // 4. Simpan Jawaban (Looping Input)
            $inputs = $request->except(['_token', 'action', 'current_step']);

            foreach ($inputs as $key => $value) {
                if (str_starts_with($key, 'q_')) {
                    $questionId = str_replace('q_', '', $key);
                    
                    // -- Logika "Lainnya" pada Radio --
                    $otherTextKey = 'other_text_' . $questionId;
                    if ($request->has($otherTextKey) && !empty($request->input($otherTextKey))) {
                        $value = $request->input($otherTextKey);
                    }

                    // -- Logika Array (Checkbox & Matrix) --
                    if (is_array($value)) {
                        $newValueArray = [];
                        foreach ($value as $k => $item) {
                            // Cek apakah ini Matrix (Key String) atau Checkbox (Key Index)
                            if (is_string($k)) {
                                // Matrix: Key adalah Label Opsi, Value adalah Nilai
                                $newValueArray[$k] = $item;
                            } else {
                                // Checkbox: Item adalah Value opsi
                                // Cek input teks pendamping checkbox
                                $chkKey = 'other_text_' . $questionId . '_' . str_replace(' ', '_', $item);
                                if ($request->has($chkKey) && !empty($request->input($chkKey))) {
                                    $newValueArray[] = $request->input($chkKey);
                                } else {
                                    $newValueArray[] = $item;
                                }
                            }
                        }
                        // Encode jadi JSON agar bisa masuk database
                        $value = json_encode($newValueArray);
                    }

                    // Simpan ke Tabel SubmissionValue
                    SubmissionValue::updateOrCreate(
                        ['submission_id' => $submission->id, 'question_id' => $questionId],
                        ['value' => $value]
                    );
                }
            }

            DB::commit();

            // 5. Response / Redirect
            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Progress tersimpan.']);
            }

            if ($action === 'submit') {
                return redirect()->route('dashboard.index')
                    ->with('success', 'Survei berhasil dikirim! Mohon tunggu review admin.');
            }

            return redirect()->back()->with('success', 'Draft berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Fungsi khusus untuk AJAX (memaksa action=draft)
    public function saveDraft(Request $request)
    {
        $request->merge(['action' => 'draft']);
        return $this->store($request);
    }

    // Helper
    private function isJson($string) {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
    public function show($id)
    {
        $submission = Submission::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->with('values')
                        ->firstOrFail();

        $questions = Question::with('options')->orderBy('order_position')->get();
        $chunks = $questions->groupBy('dimension');
        if($chunks->count() <= 1) $chunks = $questions->chunk(10);

        $answers = $submission->values->pluck('value', 'question_id')->toArray();

        return view('survey.show', compact('submission', 'chunks', 'answers'));
    }
}