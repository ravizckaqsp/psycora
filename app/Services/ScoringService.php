<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionnaireResponse;
use App\Models\RiskClassification;
use App\Models\SdqAnswer;
use App\Models\Psc17Answer;
use App\Models\SassvAnswer;

class ScoringService
{
    public function calculate(QuestionnaireResponse $response): array
    {
        $user = $response->user;

        // === HITUNG SDQ ===
        $sdqAnswers   = SdqAnswer::where('response_id', $response->id)->get();
        $sdqQuestions = Question::where('type', 'sdq')->get()->keyBy('number');
        $sdqTotal = 0;

        foreach ($sdqAnswers as $ans) {
            $q = $sdqQuestions[$ans->question_number] ?? null;
            if ($q && $q->subscale !== 'prosocial') {
                $val = $ans->answer_value;
                // Reverse scored: 0→2, 1→1, 2→0
                if ($q->reverse_scored) {
                    $val = 2 - $val;
                }
                $sdqTotal += $val;
            }
        }

        if ($sdqTotal <= 15)      $sdqCategory = 'Normal';
        elseif ($sdqTotal <= 19)  $sdqCategory = 'Borderline';
        else                       $sdqCategory = 'Abnormal';

        // === HITUNG PSC-17 ===
        $psc17Answers   = Psc17Answer::where('response_id', $response->id)->get();
        $psc17Questions = Question::where('type', 'psc17')->get()->keyBy('number');

        $internalizing = 0;
        $attention     = 0;
        $externalizing = 0;

        foreach ($psc17Answers as $ans) {
            $q = $psc17Questions[$ans->question_number] ?? null;
            if (! $q) continue;
            if ($q->subscale === 'internalizing')  $internalizing += $ans->answer_value;
            elseif ($q->subscale === 'attention')  $attention     += $ans->answer_value;
            elseif ($q->subscale === 'externalizing') $externalizing += $ans->answer_value;
        }

        $psc17Total    = $internalizing + $attention + $externalizing;
        $psc17Category = ($psc17Total >= 15 || $internalizing >= 5 || $attention >= 7 || $externalizing >= 7)
            ? 'Positif' : 'Negatif';

        // === HITUNG SAS-SV ===
        $sassvTotal = SassvAnswer::where('response_id', $response->id)->sum('answer_value');
        $cutoff     = ($user && $user->gender === 'P') ? 33 : 31;
        $sassvCategory = ($sassvTotal >= $cutoff) ? 'Kecanduan' : 'Tidak Kecanduan';

        // === RULE-BASED KLASIFIKASI ===
        [$overallRisk, $recommendation] = $this->classify($psc17Category, $sdqCategory, $sassvCategory);

        // Simpan skor ke response
        $response->update([
            'sdq_score'    => $sdqTotal,
            'psc17_score'  => $psc17Total,
            'sassv_score'  => $sassvTotal,
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        // Simpan klasifikasi
        RiskClassification::updateOrCreate(
            ['response_id' => $response->id],
            [
                'sdq_category'   => $sdqCategory,
                'psc17_category' => $psc17Category,
                'sassv_category' => $sassvCategory,
                'overall_risk'   => $overallRisk,
                'recommendation' => $recommendation,
            ]
        );

        return [
            'sdq_score'      => $sdqTotal,
            'psc17_score'    => $psc17Total,
            'sassv_score'    => $sassvTotal,
            'sdq_category'   => $sdqCategory,
            'psc17_category' => $psc17Category,
            'sassv_category' => $sassvCategory,
            'overall_risk'   => $overallRisk,
            'recommendation' => $recommendation,
        ];
    }

    private function classify(string $psc17, string $sdq, string $sassv): array
    {
        if ($psc17 === 'Negatif' && $sdq === 'Normal' && $sassv === 'Tidak Kecanduan') {
            return [
                'Tidak Berisiko Psikososial',
                'Kondisi psikososial kamu saat ini dalam keadaan baik. Terus jaga kesehatan mental dan pola aktivitas sehari-hari.'
            ];
        }

        return [
            'Berisiko Psikososial',
            'Terdapat indikasi risiko psikososial berdasarkan hasil kuesioner. Disarankan untuk berkonsultasi dengan guru BK atau tenaga profesional.'
        ];
    }
}
