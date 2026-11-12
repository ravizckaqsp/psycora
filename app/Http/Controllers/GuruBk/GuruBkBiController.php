<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;

class GuruBkBiController extends Controller
{
    public function index()
    {
        // 1. Distribusi Status Risiko Siswa Per Kelas & Skor Rata-rata
        $classesData = Classes::with(['students.questionnaireResponses' => function($q) {
            $q->where('status', 'completed')->latest('completed_at');
        }, 'students.questionnaireResponses.riskClassification'])->get()->map(function($class) {
            $totalStudents = $class->students->count();
            $completedCount = 0;
            $sdqSum = 0;
            $pscSum = 0;
            $sassvSum = 0;
            
            $risks = ['Tidak Berisiko Psikososial' => 0, 'Berisiko Psikososial' => 0];
            
            foreach ($class->students as $student) {
                $latest = $student->questionnaireResponses->first();
                if ($latest) {
                    $completedCount++;
                    $sdqSum += $latest->sdq_score;
                    $pscSum += $latest->psc17_score;
                    $sassvSum += $latest->sassv_score;
                    
                    if ($latest->riskClassification) {
                        $risk = $latest->riskClassification->overall_risk;
                        if (isset($risks[$risk])) {
                            $risks[$risk]++;
                        }
                    }
                }
            }
            
            return [
                'id' => $class->id,
                'name' => $class->name,
                'total_students' => $totalStudents,
                'completed_count' => $completedCount,
                'avg_sdq' => $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0,
                'avg_psc' => $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0,
                'avg_sassv' => $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0,
                'risks' => $risks,
            ];
        });

        // 2. Daftar Kuesioner Terbaru (Limit 5)
        $recentQuestionnaires = QuestionnaireResponse::with(['user.studentClass', 'riskClassification'])
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(5)
            ->get();

        return view('guru_bk.bi', compact('classesData', 'recentQuestionnaires'));
    }
}
