<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;

class GuruBkReportController extends Controller
{
    public function printClassReport($class_id)
    {
        $class = Classes::with(['students.siswa', 'students.questionnaireResponses' => function($q) {
            $q->where('status', 'completed')->latest('completed_at');
        }, 'students.questionnaireResponses.riskClassification'])->findOrFail($class_id);

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

        $avgSdq = $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0;
        $avgPsc = $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0;
        $avgSassv = $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0;

        return view('guru_bk.laporan-kelas.print', compact(
            'class', 
            'totalStudents', 
            'completedCount', 
            'avgSdq', 
            'avgPsc', 
            'avgSassv', 
            'risks'
        ));
    }

    public function exportClassReport($class_id)
    {
        $class = Classes::findOrFail($class_id);
        
        $students = User::role('siswa')
            ->whereHas('siswa', function($q) use ($class_id) {
                $q->where('class_id', $class_id);
            })
            ->with(['siswa', 'questionnaireResponses' => function($q) {
                $q->where('status', 'completed')->latest('completed_at');
            }, 'questionnaireResponses.riskClassification'])
            ->get();

        $fileName = 'Laporan_Kelas_' . str_replace(' ', '_', $class->name) . '_' . date('Ymd') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($students, $class) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to ensure Excel opens CSV with correct encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header Laporan
            fputcsv($file, ['LAPORAN KONDISI PSIKOSOSIAL KELAS']);
            fputcsv($file, ['Kelas', $class->name]);
            fputcsv($file, ['Tahun Ajaran', $class->academic_year ?? '-']);
            fputcsv($file, ['Tanggal Unduh', date('d-m-Y H:i:s')]);
            fputcsv($file, []); // Baris kosong

            // Kolom Header Tabel
            fputcsv($file, [
                'No', 
                'NIS', 
                'Nama Siswa', 
                'Jenis Kelamin', 
                'Status Partisipasi', 
                'Tanggal Pengerjaan', 
                'Skor SDQ', 
                'Skor PSC-17', 
                'Skor SAS-SV', 
                'Tingkat Risiko', 
                'Rekomendasi / Rencana Tindakan'
            ]);

            foreach ($students as $index => $student) {
                $latest = $student->questionnaireResponses->first();
                
                $gender = (strtoupper($student->gender) === 'L') ? 'Laki-laki' : 'Perempuan';
                $participation = $latest ? 'Selesai' : 'Belum Berpartisipasi';
                $completedAt = $latest && $latest->completed_at ? $latest->completed_at->format('d-m-Y H:i') : '-';
                $sdq = $latest ? $latest->sdq_score : '-';
                $psc = $latest ? $latest->psc17_score : '-';
                $sas = $latest ? $latest->sassv_score : '-';
                $risk = $latest && $latest->riskClassification ? $latest->riskClassification->overall_risk : '-';
                $rec = $latest && $latest->riskClassification ? $latest->riskClassification->recommendation : '-';

                fputcsv($file, [
                    $index + 1,
                    $student->nis ?? '-',
                    $student->name,
                    $gender,
                    $participation,
                    $completedAt,
                    $sdq,
                    $psc,
                    $sas,
                    $risk,
                    $rec
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printSchoolReport()
    {
        $classes = Classes::with(['students.siswa', 'students.questionnaireResponses' => function($q) {
            $q->where('status', 'completed')->latest('completed_at');
        }, 'students.questionnaireResponses.riskClassification'])->get();

        $classesData = $classes->map(function($class) {
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

            $riskCount = $risks['Berisiko Psikososial'] ?? 0;
            $riskPercentage = $completedCount > 0 ? round(($riskCount / $completedCount) * 100, 1) : 0;
            
            return [
                'id' => $class->id,
                'name' => $class->name,
                'academic_year' => $class->academic_year ?? '-',
                'total_students' => $totalStudents,
                'completed_count' => $completedCount,
                'participation_rate' => $totalStudents > 0 ? round(($completedCount / $totalStudents) * 100, 1) : 0,
                'avg_sdq' => $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0,
                'avg_psc' => $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0,
                'avg_sassv' => $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0,
                'risk_count' => $riskCount,
                'risk_percentage' => $riskPercentage,
                'no_risk_count' => $risks['Tidak Berisiko Psikososial'] ?? 0,
            ];
        });

        // Urutkan kelas berdasarkan jumlah siswa berisiko terbanyak secara desending
        $sortedByRisk = $classesData->sortByDesc('risk_count')->values();

        // Cari kelas dengan risiko terbanyak
        $highestRiskClass = $sortedByRisk->first();

        // Hitung total satu sekolah
        $schoolTotalStudents = $classesData->sum('total_students');
        $schoolCompletedCount = $classesData->sum('completed_count');
        $schoolRiskCount = $classesData->sum('risk_count');
        $schoolNoRiskCount = $classesData->sum('no_risk_count');

        // Rata-rata satu sekolah
        $schoolAvgSdq = $schoolCompletedCount > 0 ? round($classesData->sum(fn($c) => $c['avg_sdq'] * $c['completed_count']) / $schoolCompletedCount, 1) : 0;
        $schoolAvgPsc = $schoolCompletedCount > 0 ? round($classesData->sum(fn($c) => $c['avg_psc'] * $c['completed_count']) / $schoolCompletedCount, 1) : 0;
        $schoolAvgSassv = $schoolCompletedCount > 0 ? round($classesData->sum(fn($c) => $c['avg_sassv'] * $c['completed_count']) / $schoolCompletedCount, 1) : 0;

        return view('guru_bk.laporan-sekolah.print', compact(
            'classesData',
            'sortedByRisk',
            'highestRiskClass',
            'schoolTotalStudents',
            'schoolCompletedCount',
            'schoolRiskCount',
            'schoolNoRiskCount',
            'schoolAvgSdq',
            'schoolAvgPsc',
            'schoolAvgSassv'
        ));
    }

    public function exportSchoolReport()
    {
        $classes = Classes::with(['students.siswa', 'students.questionnaireResponses' => function($q) {
            $q->where('status', 'completed')->latest('completed_at');
        }, 'students.questionnaireResponses.riskClassification'])->get();

        $classesData = $classes->map(function($class) {
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

            $riskCount = $risks['Berisiko Psikososial'] ?? 0;
            
            return [
                'name' => $class->name,
                'total_students' => $totalStudents,
                'completed_count' => $completedCount,
                'participation_rate' => $totalStudents > 0 ? round(($completedCount / $totalStudents) * 100, 1) : 0,
                'avg_sdq' => $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0,
                'avg_psc' => $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0,
                'avg_sassv' => $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0,
                'risk_count' => $riskCount,
            ];
        })->sortByDesc('risk_count')->values();

        $fileName = 'Laporan_Sekolah_Per_Kelas_' . date('Ymd') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($classesData) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to ensure Excel opens CSV with correct encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header Laporan
            fputcsv($file, ['LAPORAN RINGKASAN KONDISI KELAS (SATU SEKOLAH)']);
            fputcsv($file, ['Tanggal Unduh', date('d-m-Y H:i:s')]);
            fputcsv($file, []); // Baris kosong

            // Kolom Header Tabel
            fputcsv($file, [
                'Peringkat Risiko', 
                'Nama Kelas',
                'Total Siswa', 
                'Jumlah Partisipan', 
                'Tingkat Partisipasi (%)', 
                'Siswa Berisiko Psikososial', 
                'Rata-rata SDQ', 
                'Rata-rata PSC-17', 
                'Rata-rata SAS-SV'
            ]);

            foreach ($classesData as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row['name'],
                    $row['total_students'],
                    $row['completed_count'],
                    $row['participation_rate'] . '%',
                    $row['risk_count'],
                    $row['avg_sdq'],
                    $row['avg_psc'],
                    $row['avg_sassv']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printStudentReport($student_id)
    {
        $student = User::role('siswa')->with('studentClass')->findOrFail($student_id);

        $latestResponse = QuestionnaireResponse::with([
            'riskClassification',
            'sdqAnswers',
            'psc17Answers',
            'sassvAnswers'
        ])
            ->where('user_id', $student_id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();

        return view('guru_bk.laporan-siswa.print', compact('student', 'latestResponse'));
    }

    protected function getFilteredStudents(Request $request)
    {
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

        return $students;
    }

    public function printFilteredReport(Request $request)
    {
        $students = $this->getFilteredStudents($request);

        $totalStudents = $students->count();
        $completedCount = 0;
        $sdqSum = 0;
        $pscSum = 0;
        $sassvSum = 0;
        $risks = ['Tidak Berisiko Psikososial' => 0, 'Berisiko Psikososial' => 0];

        foreach ($students as $student) {
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

        $avgSdq = $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0;
        $avgPsc = $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0;
        $avgSassv = $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0;

        $appliedFilters = [];
        if ($request->filled('search')) {
            $appliedFilters['Kata Kunci'] = '"' . $request->input('search') . '"';
        }
        if ($request->filled('class_id')) {
            $class = Classes::find($request->input('class_id'));
            if ($class) {
                $appliedFilters['Kelas'] = $class->name;
            }
        }
        if ($request->filled('gender')) {
            $appliedFilters['Jenis Kelamin'] = $request->input('gender') === 'L' ? 'Laki-laki' : 'Perempuan';
        }
        if ($request->filled('risk_level')) {
            $appliedFilters['Tingkat Risiko'] = $request->input('risk_level');
        }
        if ($request->filled('participation')) {
            $appliedFilters['Partisipasi'] = $request->input('participation') === 'completed' ? 'Selesai Kuesioner' : 'Belum Kuesioner';
        }
        if ($request->filled('sdq_category')) {
            $appliedFilters['Kategori SDQ'] = $request->input('sdq_category');
        }
        if ($request->filled('psc17_category')) {
            $appliedFilters['Kategori PSC-17'] = $request->input('psc17_category');
        }
        if ($request->filled('sassv_category')) {
            $appliedFilters['Kategori SAS-SV'] = $request->input('sassv_category');
        }

        return view('guru_bk.laporan-filter.print', compact(
            'students', 
            'totalStudents', 
            'completedCount', 
            'avgSdq', 
            'avgPsc', 
            'avgSassv', 
            'risks',
            'appliedFilters'
        ));
    }

    public function exportFilteredReport(Request $request)
    {
        $students = $this->getFilteredStudents($request);

        $fileName = 'Laporan_Siswa_Terfilter_' . date('Ymd') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($students, $request) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to ensure Excel opens CSV with correct encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header Laporan
            fputcsv($file, ['LAPORAN KONDISI PSIKOSOSIAL SISWA (HASIL FILTER)']);
            fputcsv($file, ['Tanggal Unduh', date('d-m-Y H:i:s')]);
            
            // Applied filters info
            if ($request->filled('search')) {
                fputcsv($file, ['Filter Pencarian', $request->input('search')]);
            }
            if ($request->filled('class_id')) {
                $class = Classes::find($request->input('class_id'));
                fputcsv($file, ['Filter Kelas', $class ? $class->name : '-']);
            }
            if ($request->filled('risk_level')) {
                fputcsv($file, ['Filter Risiko', $request->input('risk_level')]);
            }
            if ($request->filled('participation')) {
                fputcsv($file, ['Filter Status Partisipasi', $request->input('participation') === 'completed' ? 'Selesai Kuesioner' : 'Belum Kuesioner']);
            }
            if ($request->filled('gender')) {
                fputcsv($file, ['Filter Jenis Kelamin', $request->input('gender') === 'L' ? 'Laki-laki' : 'Perempuan']);
            }
            
            fputcsv($file, []); // Baris kosong

            // Kolom Header Tabel
            fputcsv($file, [
                'No', 
                'NIS', 
                'Nama Siswa', 
                'Kelas',
                'Jenis Kelamin', 
                'Status Partisipasi', 
                'Tanggal Pengerjaan', 
                'Skor SDQ', 
                'Skor PSC-17', 
                'Skor SAS-SV', 
                'Tingkat Risiko', 
                'Rekomendasi / Rencana Tindakan'
            ]);

            foreach ($students as $index => $student) {
                $latest = $student->questionnaireResponses->first();
                
                $gender = (strtoupper($student->gender) === 'L') ? 'Laki-laki' : 'Perempuan';
                $participation = $latest ? 'Selesai' : 'Belum Berpartisipasi';
                $completedAt = $latest && $latest->completed_at ? $latest->completed_at->format('d-m-Y H:i') : '-';
                $sdq = $latest ? $latest->sdq_score : '-';
                $psc = $latest ? $latest->psc17_score : '-';
                $sas = $latest ? $latest->sassv_score : '-';
                $risk = $latest && $latest->riskClassification ? $latest->riskClassification->overall_risk : '-';
                $rec = $latest && $latest->riskClassification ? $latest->riskClassification->recommendation : '-';
                $className = $student->studentClass->name ?? '-';

                fputcsv($file, [
                    $index + 1,
                    $student->nis ?? '-',
                    $student->name,
                    $className,
                    $gender,
                    $participation,
                    $completedAt,
                    $sdq,
                    $psc,
                    $sas,
                    $risk,
                    $rec
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
