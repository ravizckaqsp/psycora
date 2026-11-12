<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruBkDashboardController extends Controller
{
    public function index()
    {
        // 1. Ringkasan Statistik
        $totalStudents = User::role('siswa')->count();
        
        $completedStudents = User::role('siswa')->whereHas('questionnaireResponses', function($q) {
            $q->where('status', 'completed');
        })->count();
        
        $nonCompletedStudents = $totalStudents - $completedStudents;
        
        $participationRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100) : 0;

        // 2. Ringkasan Jumlah Siswa Per Kategori Risiko (Berdasarkan Kuesioner Terakhir)
        $students = User::role('siswa')
            ->with([
                'questionnaireResponses' => function($q) {
                    $q->where('status', 'completed')->latest('completed_at');
                },
                'questionnaireResponses.riskClassification',
                'questionnaireResponses.sdqAnswers',
                'questionnaireResponses.psc17Answers'
            ])
            ->get();

        $riskCounts = [
            'Tidak Berisiko Psikososial' => 0,
            'Berisiko Psikososial' => 0,
        ];
        
        $highRiskStudentsCount = 0;

        // Analisis Per Gender (dari BI)
        $genderRisks = [
            'L' => ['Tidak Berisiko Psikososial' => 0, 'Berisiko Psikososial' => 0],
            'P' => ['Tidak Berisiko Psikososial' => 0, 'Berisiko Psikososial' => 0]
        ];

        // Hitung Kategori per Instrumen
        $sdqCounts = [
            'Normal' => 0,
            'Borderline' => 0,
            'Abnormal' => 0,
        ];

        $pscCounts = [
            'Negatif' => 0,
            'Positif' => 0,
        ];

        $sassvCounts = [
            'Tidak Kecanduan' => 0,
            'Kecanduan' => 0,
        ];

        // Rata-rata Skor Subskala SDQ & PSC-17
        $sdqSubscaleSums = [
            'emotional' => 0,
            'conduct' => 0,
            'hyperactivity' => 0,
            'peer' => 0,
            'prosocial' => 0,
        ];
        
        $pscSubscaleSums = [
            'internalizing' => 0,
            'attention' => 0,
            'externalizing' => 0,
        ];

        // Distribusi Rentang Skor SAS-SV
        $sassvDistribution = [
            '10-20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-60' => 0,
        ];

        $completedResponseCount = 0;

        foreach ($students as $student) {
            $latest = $student->questionnaireResponses->first();
            if ($latest && $latest->riskClassification) {
                $completedResponseCount++;
                $risk = $latest->riskClassification->overall_risk;
                if (array_key_exists($risk, $riskCounts)) {
                    $riskCounts[$risk]++;
                }
                if ($risk === 'Berisiko Psikososial') {
                    $highRiskStudentsCount++;
                }

                // Hitung gender (dari BI)
                $g = ($student->gender === 'L' || $student->gender === 'l') ? 'L' : 'P';
                if (isset($genderRisks[$g][$risk])) {
                    $genderRisks[$g][$risk]++;
                }

                // Hitung SDQ Category
                $sdqCat = $latest->riskClassification->sdq_category;
                if (isset($sdqCounts[$sdqCat])) {
                    $sdqCounts[$sdqCat]++;
                }

                // Hitung PSC-17 Category
                $pscCat = $latest->riskClassification->psc17_category;
                if (isset($pscCounts[$pscCat])) {
                    $pscCounts[$pscCat]++;
                }

                // Hitung SAS-SV Category
                $sassvCat = $latest->riskClassification->sassv_category;
                if (isset($sassvCounts[$sassvCat])) {
                    $sassvCounts[$sassvCat]++;
                }

                // Hitung SDQ Subscores
                $sdqSub = $latest->getSdqSubscores();
                foreach ($sdqSub as $key => $val) {
                    if (isset($sdqSubscaleSums[$key])) {
                        $sdqSubscaleSums[$key] += $val;
                    }
                }

                // Hitung PSC-17 Subscores
                $pscSub = $latest->getPsc17Subscores();
                foreach ($pscSub as $key => $val) {
                    if (isset($pscSubscaleSums[$key])) {
                        $pscSubscaleSums[$key] += $val;
                    }
                }

                // Hitung SAS-SV Distribution Range
                $sassvScore = $latest->sassv_score;
                if ($sassvScore <= 20) {
                    $sassvDistribution['10-20']++;
                } elseif ($sassvScore <= 30) {
                    $sassvDistribution['21-30']++;
                } elseif ($sassvScore <= 40) {
                    $sassvDistribution['31-40']++;
                } else {
                    $sassvDistribution['41-60']++;
                }
            }
        }

        $sdqSubscaleAverages = [];
        foreach ($sdqSubscaleSums as $key => $sum) {
            $sdqSubscaleAverages[$key] = $completedResponseCount > 0 
                ? round($sum / $completedResponseCount, 2) 
                : 0;
        }

        $pscSubscaleAverages = [];
        foreach ($pscSubscaleSums as $key => $sum) {
            $pscSubscaleAverages[$key] = $completedResponseCount > 0 
                ? round($sum / $completedResponseCount, 2) 
                : 0;
        }

        // 3. Rata-rata Skor per Kelas (dari BI)
        $classes = Classes::with([
            'students.questionnaireResponses' => function($q) {
                $q->where('status', 'completed')->latest('completed_at');
            },
            'students.questionnaireResponses.riskClassification'
        ])->get();

        $classLabels = [];
        $classIds = [];
        $classSdq = [];
        $classPsc = [];
        $classSassv = [];
        $classRiskHigh = [];
        $classRiskLow = [];
        $classRanking = [];

        foreach ($classes as $class) {
            if ($class->students->isEmpty()) {
                continue;
            }

            $classLabels[] = $class->name;
            $classIds[] = $class->id;
            $completedCount = 0;
            $sdqSum = 0;
            $pscSum = 0;
            $sassvSum = 0;
            $highRiskCount = 0;
            $lowRiskCount = 0;

            foreach ($class->students as $student) {
                $latest = $student->questionnaireResponses->first();
                if ($latest) {
                    $completedCount++;
                    $sdqSum += $latest->sdq_score;
                    $pscSum += $latest->psc17_score;
                    $sassvSum += $latest->sassv_score;

                    if ($latest->riskClassification) {
                        $risk = $latest->riskClassification->overall_risk;
                        if ($risk === 'Berisiko Psikososial') {
                            $highRiskCount++;
                        } else {
                            $lowRiskCount++;
                        }
                    }
                }
            }

            $classSdq[] = $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0;
            $classPsc[] = $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0;
            $classSassv[] = $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0;
            
            $classRiskHigh[] = $highRiskCount;
            $classRiskLow[] = $lowRiskCount;

            $classRanking[] = [
                'name' => $class->name,
                'id' => $class->id,
                'high_risk_count' => $highRiskCount
            ];
        }

        // Urutkan ranking kelas berdasarkan jumlah siswa berisiko secara descending
        usort($classRanking, function($a, $b) {
            return $b['high_risk_count'] <=> $a['high_risk_count'];
        });

        // Batasi peringkat kelas hanya untuk top 5 saja
        $classRanking = array_slice($classRanking, 0, 5);

        $rankingClassLabels = array_column($classRanking, 'name');
        $rankingClassData = array_column($classRanking, 'high_risk_count');
        $rankingClassIds = array_column($classRanking, 'id');

        return view('guru_bk.dashboard', compact(
            'totalStudents', 'completedStudents', 'nonCompletedStudents', 'participationRate',
            'riskCounts', 'highRiskStudentsCount', 'genderRisks',
            'classLabels', 'classIds', 'classSdq', 'classPsc', 'classSassv',
            'sdqCounts', 'pscCounts', 'sassvCounts',
            'sdqSubscaleAverages', 'pscSubscaleAverages', 'sassvDistribution',
            'classRiskHigh', 'classRiskLow', 'rankingClassLabels', 'rankingClassData', 'rankingClassIds'
        ));
    }
}