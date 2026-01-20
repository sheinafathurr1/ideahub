<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. CEK ROLE: JIKA ADMIN, LEMPAR KE HALAMAN ADMIN
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 2. JIKA BUKAN ADMIN (USER BIASA), JALANKAN LOGIKA DASHBOARD USER SEPERTI BIASA
        $user = Auth::user();
        
        $activeSubmission = Submission::where('user_id', $user->id)
                        ->whereIn('status', ['draft', 'rejected'])
                        ->first();

        $pendingSubmission = Submission::where('user_id', $user->id)
                        ->where('status', 'submitted')
                        ->first();

        $completedSubmissions = Submission::where('user_id', $user->id)
                        ->where('status', 'accepted')
                        ->orderBy('submitted_at', 'desc')
                        ->get();

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
            $progress = min(round(($activeSubmission->current_step / 10) * 100), 95);
        } 
        elseif ($pendingSubmission) {
            $uiState = 'submitted';
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