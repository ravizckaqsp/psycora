<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\QuestionnaireResponse;
use App\Models\RiskClassification;
use Illuminate\Http\Request;

class GuruBkSiswaController extends Controller
{
    public function index(Request $request)
    {
        $classes = Classes::orderBy('name')->get();

        $query = User::role('siswa')->with([
            'studentClass', 
            'siswa',
            'questionnaireResponses' => function($q) {
                $q->where('status', 'completed')->latest('completed_at');
            }, 
            'questionnaireResponses.riskClassification'
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nis', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('class_id')) {
            $query->whereHas('siswa', function($sq) use ($request) {
                $sq->where('class_id', $request->input('class_id'));
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('participation')) {
            $participation = $request->input('participation');
            if ($participation === 'completed') {
                $query->whereHas('questionnaireResponses', function($q) {
                    $q->where('status', 'completed');
                });
            } elseif ($participation === 'none') {
                $query->whereDoesntHave('questionnaireResponses', function($q) {
                    $q->where('status', 'completed');
                });
            }
        }

        $students = $query->get();

        // Penyaringan tingkat risiko terbaru berbasis PHP Collection
        if ($request->filled('risk_level')) {
            $risk = $request->input('risk_level');
            $students = $students->filter(function($student) use ($risk) {
                $latest = $student->questionnaireResponses->first();
                if ($latest && $latest->riskClassification) {
                    return $latest->riskClassification->overall_risk === $risk;
                }
                return false;
            })->values();
        }

        // Filter kategori SDQ
        if ($request->filled('sdq_category')) {
            $sdqCat = $request->input('sdq_category');
            $students = $students->filter(function($student) use ($sdqCat) {
                $latest = $student->questionnaireResponses->first();
                if ($latest && $latest->riskClassification) {
                    return $latest->riskClassification->sdq_category === $sdqCat;
                }
                return false;
            })->values();
        }

        // Filter kategori PSC-17
        if ($request->filled('psc17_category')) {
            $pscCat = $request->input('psc17_category');
            $students = $students->filter(function($student) use ($pscCat) {
                $latest = $student->questionnaireResponses->first();
                if ($latest && $latest->riskClassification) {
                    return $latest->riskClassification->psc17_category === $pscCat;
                }
                return false;
            })->values();
        }

        // Filter kategori SAS-SV
        if ($request->filled('sassv_category')) {
            $sassvCat = $request->input('sassv_category');
            $students = $students->filter(function($student) use ($sassvCat) {
                $latest = $student->questionnaireResponses->first();
                if ($latest && $latest->riskClassification) {
                    return $latest->riskClassification->sassv_category === $sassvCat;
                }
                return false;
            })->values();
        }

        return view('guru_bk.siswa.index', compact('students', 'classes'));
    }

    public function show($id)
    {
        $student = User::role('siswa')->with('studentClass')->findOrFail($id);

        // Kuesioner riwayat lengkap (urut tanggal terlama ke terbaru untuk grafik)
        $history = QuestionnaireResponse::with([
            'riskClassification',
            'sdqAnswers',
            'psc17Answers',
            'sassvAnswers'
        ])
            ->where('user_id', $id)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'asc')
            ->get();

        // Kuesioner riwayat terbalik (untuk tabel riwayat, terbaru di atas)
        $tableHistory = $history->reverse()->values();

        // Grafik linear pribadi
        $chartLabels = [];
        $chartSdq = [];
        $chartPsc = [];
        $chartSassv = [];

        foreach ($history as $response) {
            $chartLabels[] = $response->completed_at->format('d M Y');
            $chartSdq[] = $response->sdq_score;
            $chartPsc[] = $response->psc17_score;
            $chartSassv[] = $response->sassv_score;
        }

        $latestResponse = $tableHistory->first();

        // Ambil semua pertanyaan untuk melihat detail isi instrumen
        $questions = \App\Models\Question::all();
        $sdqQuestions = $questions->where('type', 'sdq')->keyBy('number');
        $pscQuestions = $questions->where('type', 'psc17')->keyBy('number');
        $sasQuestions = $questions->where('type', 'sassv')->keyBy('number');

        return view('guru_bk.siswa.show', compact(
            'student', 'history', 'tableHistory', 'latestResponse',
            'chartLabels', 'chartSdq', 'chartPsc', 'chartSassv',
            'sdqQuestions', 'pscQuestions', 'sasQuestions'
        ));
    }

    public function storeRecommendation(Request $request, $id)
    {
        $request->validate([
            'recommendation' => 'required|string|max:1000',
        ]);

        $latestResponse = QuestionnaireResponse::where('user_id', $id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();

        if (!$latestResponse) {
            return redirect()->back()->with('error', 'Siswa belum menyelesaikan kuesioner apapun.');
        }

        $classification = RiskClassification::firstOrNew(['response_id' => $latestResponse->id]);
        $classification->recommendation = $request->input('recommendation');
        $classification->save();

        return redirect()->back()->with('success', 'Rekomendasi tindakan preventif berhasil disimpan.');
    }
}
