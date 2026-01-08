<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Cek yang Perlu Aksi User (Draft ATAU Rejected)
        $activeSubmission = Submission::where('user_id', $user->id)
                        ->whereIn('status', ['draft', 'rejected'])
                        ->first();

        // 2. Cek yang Sedang Direview (Submitted) - Read Only
        $pendingSubmission = Submission::where('user_id', $user->id)
                        ->where('status', 'submitted')
                        ->first();

        // 3. History yang Selesai (Accepted)
        $completedSubmissions = Submission::where('user_id', $user->id)
                        ->where('status', 'accepted')
                        ->orderBy('submitted_at', 'desc')
                        ->get();

        // LOGIKA UI STATE
        $uiState = 'new'; 
        $progress = 0;
        $feedback = null;

        if ($activeSubmission) {
            if ($activeSubmission->status == 'rejected') {
                $uiState = 'rejected';
                $feedback = $activeSubmission->admin_feedback;
            } else {
                $uiState = 'draft';
            }
            // Hitung progress (asumsi 10 step, sesuaikan dengan realita)
            $progress = min(round(($activeSubmission->current_step / 10) * 100), 95);
        } 
        elseif ($pendingSubmission) {
            $uiState = 'submitted'; // State menunggu
        }

        return view('dashboard.index', compact(
            'user', 
            'activeSubmission', 
            'pendingSubmission', 
            'completedSubmissions', 
            'uiState', 
            'progress', 
            'feedback'
        ));
    }
}