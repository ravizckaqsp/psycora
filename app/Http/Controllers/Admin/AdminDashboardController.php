<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Question;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalGuruBk = User::role('guru_bk')->count();
        $totalSiswa = User::role('siswa')->count();
        $totalSoal = Question::count();
        $totalKelas = Classes::count();

        // 5 Aktivitas Kuesioner Terbaru Sekolah
        $recentActivities = QuestionnaireResponse::with(['user.studentClass', 'riskClassification'])
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalGuruBk', 'totalSiswa', 'totalSoal', 'totalKelas', 'recentActivities'
        ));
    }
}