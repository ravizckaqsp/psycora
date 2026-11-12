<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireResponse;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $responses = QuestionnaireResponse::with('riskClassification')
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest('completed_at')
            ->paginate(10);

        // Data untuk chart perkembangan (semua history)
        $chartData = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->oldest('completed_at')
            ->get(['completed_at', 'sdq_score', 'psc17_score', 'sassv_score']);

        return view('siswa.riwayat.index', compact('responses', 'chartData'));
    }

    public function show($id)
    {
        $response = QuestionnaireResponse::with('riskClassification')
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->findOrFail($id);

        return view('siswa.riwayat.show', compact('response'));
    }
}
