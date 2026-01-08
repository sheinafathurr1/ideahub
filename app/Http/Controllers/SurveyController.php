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
    // --- TAMPILKAN FORM ---
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

        // LOGIKA BARU: Ambil jika Draft ATAU Rejected (Keduanya bisa diedit)
        $submission = Submission::where('user_id', Auth::id())
                        ->whereIn('status', ['draft', 'rejected']) 
                        ->first();
        
        $existingAnswers = [];
        if ($submission) {
            $values = SubmissionValue::where('submission_id', $submission->id)->get();
            foreach ($values as $val) {
                $decoded = json_decode($val->value, true);
                $existingAnswers[$val->question_id] = is_array($decoded) ? $decoded : $val->value;
            }
        }

        return view('survey.index', compact('chunks', 'submission', 'existingAnswers'));
    }

    // --- SIMPAN DRAFT (AJAX) ---
    public function saveDraft(Request $request)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            // 1. Get/Create Submission
            $submission = Submission::firstOrCreate(
                ['user_id' => $user->id, 'status' => 'draft'],
                ['current_step' => 1]
            );

            if ($request->has('current_step')) {
                $submission->update(['current_step' => $request->current_step]);
            }

            // 2. Loop Inputs
            $inputs = $request->except(['_token', 'current_step']);

            foreach ($inputs as $key => $value) {
                // Filter hanya input pertanyaan (q_1, q_2, dst)
                if (str_starts_with($key, 'q_')) {
                    $questionId = str_replace('q_', '', $key);
                    
                    // --- LOGIKA INPUT "LAINNYA" (RADIO) ---
                    // Cek apakah ada input teks pendamping untuk pertanyaan ini?
                    // Name di view: "other_text_{qid}"
                    $otherTextKey = 'other_text_' . $questionId;
                    
                    if ($request->has($otherTextKey)) {
                        $customText = $request->input($otherTextKey);
                        if (!empty($customText)) {
                            // Timpa value (misal "Lainnya") dengan teks user (misal "Universitas X")
                            $value = $customText;
                        }
                    }

                    // --- LOGIKA INPUT "LAINNYA" (CHECKBOX) ---
                    // Jika Checkbox, value adalah Array. Kita perlu cek satu-satu.
                    if (is_array($value)) {
                        $newValueArray = [];
                        foreach ($value as $item) {
                            // Cek apakah item ini punya teks pendamping?
                            // Name di view: "other_text_{qid}_{item}"
                            // Kita sanitize item agar aman jadi key array (misal spasi jadi underscore)
                            $chkKey = 'other_text_' . $questionId . '_' . str_replace(' ', '_', $item);
                            
                            if ($request->has($chkKey) && !empty($request->input($chkKey))) {
                                $newValueArray[] = $request->input($chkKey); // Simpan teks customnya
                            } else {
                                $newValueArray[] = $item; // Simpan value aslinya
                            }
                        }
                        $value = json_encode($newValueArray);
                    }

                    // Simpan ke DB
                    SubmissionValue::updateOrCreate(
                        ['submission_id' => $submission->id, 'question_id' => $questionId],
                        ['value' => $value]
                    );
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Draft tersimpan']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // --- FINAL SUBMIT ---
    public function store(Request $request)
    {
        $this->saveDraft($request);

        // Cari yang Draft atau Rejected (yang sedang dikerjakan)
        $submission = Submission::where('user_id', Auth::id())
                        ->whereIn('status', ['draft', 'rejected'])
                        ->first();

        if ($submission) {
            $submission->update([
                'status' => 'submitted', // Ubah status jadi Submitted (Menunggu Review)
                'submitted_at' => now(),
            ]);
        }

        return redirect()->route('dashboard.index')->with('success', 'Survei berhasil dikirim! Mohon tunggu review admin.');
    }

    // --- DETAIL HISTORY ---
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