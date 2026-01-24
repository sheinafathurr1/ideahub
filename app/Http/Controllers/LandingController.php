<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\News;
use App\Models\Submission;
use App\Models\Question;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    // Home Page
    public function index()
    {
        // Ambil 3 berita terbaru yang published
        $latestNews = News::published()
                          ->latest('published_at')
                          ->take(3)
                          ->get();

        // Hitung total universitas yang sudah accepted
        $totalUniversities = User::where('role', 'user')
                                 ->whereHas('acceptedSubmission')
                                 ->count();

        return view('landing.home', compact('latestNews', 'totalUniversities'));
    }

    // Browse Universities
    public function universities(Request $request)
    {
        $query = User::where('role', 'user')
                     ->whereHas('acceptedSubmission');

        // Filter By Type of Support (Q38)
        if ($request->has('support') && $request->support != '') {
            $supportType = $request->support;
            
            $query->whereHas('acceptedSubmission.values', function($q) use ($supportType) {
                // Cari pertanyaan Q38 (fasilitas)
                $q38 = Question::where('code', 'q38')->first();
                
                if ($q38) {
                    $q->where('question_id', $q38->id)
                      ->where('value', 'LIKE', "%{$supportType}%");
                }
            });
        }

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('university_name', 'LIKE', "%{$search}%");
        }

        $universities = $query->with('acceptedSubmission')
                              ->orderBy('university_name', 'asc')
                              ->paginate(9);

        // Ambil list fasilitas untuk filter
        $q38 = Question::where('code', 'q38')->with('options')->first();
        $supportTypes = $q38 ? $q38->options->pluck('option_label', 'value') : collect();

        $universities->appends($request->all());

        $universities = $query->with('acceptedSubmission')
                      ->orderBy('university_name', 'asc')
                      ->paginate(9);

        $universities->appends($request->all());

        return view('landing.universities', compact('universities', 'supportTypes'));
    }

    // University Detail
    public function universityDetail($slug)
    {
        $university = User::where('slug', $slug)
                          ->where('role', 'user')
                          ->with(['acceptedSubmission.values'])
                          ->firstOrFail();

        // Pastikan universitas punya submission accepted
        if (!$university->acceptedSubmission) {
            abort(404, 'Data universitas belum tersedia.');
        }

        $submission = $university->acceptedSubmission;

        // Mapping jawaban
        $answers = $submission->values->pluck('value', 'question_id')->toArray();

        // Ambil pertanyaan penting
        $questions = Question::with('options')
                             ->whereIn('code', ['q6', 'q6a', 'q9', 'q38'])
                             ->get()
                             ->keyBy('code');

        // Decode JSON answers
        foreach ($answers as $qid => $val) {
            if ($this->isJson($val)) {
                $answers[$qid] = json_decode($val, true);
            }
        }

        return view('landing.university-detail', compact('university', 'submission', 'answers', 'questions'));
    }

    // News List
    public function news()
    {
        $news = News::published()
                    ->latest('published_at')
                    ->paginate(9);

        return view('landing.news', compact('news'));
    }

    // News Detail
    public function newsDetail($slug)
    {
        $news = News::published()
                    ->where('slug', $slug)
                    ->firstOrFail();

        return view('landing.news-detail', compact('news'));
    }

    // Helper: Check JSON
    private function isJson($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
}