<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireResponse;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('studentClass');

        // Kuesioner terakhir yang selesai
        $lastResponse = QuestionnaireResponse::with('riskClassification')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();

        // Riwayat 5 terakhir untuk mini chart
        $history = QuestionnaireResponse::with('riskClassification')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(5)
            ->get()
            ->reverse()
            ->values();

        // Total kuesioner selesai
        $totalCompleted = QuestionnaireResponse::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Apakah ada draft yang sedang berjalan
        $hasDraft = QuestionnaireResponse::where('user_id', $user->id)
            ->where('status', 'draft')
            ->exists();

        return view('siswa.dashboard', compact(
            'user', 'lastResponse', 'history', 'totalCompleted', 'hasDraft'
        ));
    }
}