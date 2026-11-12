<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireResponse extends Model
{
    protected $fillable = [
        'user_id', 'sdq_score', 'psc17_score',
        'sassv_score', 'status', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'sdq_score'    => 'integer',
        'psc17_score'  => 'integer',
        'sassv_score'  => 'integer',
    ];

    public function riskClassification()
    {
        return $this->hasOne(RiskClassification::class, 'response_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sdqAnswers()
    {
        return $this->hasMany(SdqAnswer::class, 'response_id');
    }

    public function psc17Answers()
    {
        return $this->hasMany(Psc17Answer::class, 'response_id');
    }

    public function sassvAnswers()
    {
        return $this->hasMany(SassvAnswer::class, 'response_id');
    }

    public function getSdqSubscores()
    {
        $answers = $this->sdqAnswers->pluck('answer_value', 'question_number')->toArray();
        
        $subscales = [
            'prosocial' => [1, 4, 9, 17, 20],
            'hyperactivity' => [2, 10, 15, 21, 25],
            'emotional' => [3, 8, 13, 16, 24],
            'conduct' => [5, 7, 12, 18, 22],
            'peer' => [6, 11, 14, 19, 23]
        ];
        
        $reversed = [7, 11, 14, 21, 25];
        
        $scores = [];
        foreach ($subscales as $name => $questions) {
            $sum = 0;
            foreach ($questions as $qNum) {
                if (isset($answers[$qNum])) {
                    $val = $answers[$qNum];
                    if (in_array($qNum, $reversed)) {
                        $val = 2 - $val;
                    }
                    $sum += $val;
                }
            }
            $scores[$name] = $sum;
        }
        
        return $scores;
    }

    public function getPsc17Subscores()
    {
        $answers = $this->psc17Answers->pluck('answer_value', 'question_number')->toArray();
        
        $subscales = [
            'internalizing' => [2, 6, 9, 11, 15],
            'attention' => [1, 3, 7, 13, 17],
            'externalizing' => [4, 5, 8, 10, 12, 14, 16]
        ];
        
        $scores = [];
        foreach ($subscales as $name => $questions) {
            $sum = 0;
            foreach ($questions as $qNum) {
                if (isset($answers[$qNum])) {
                    $sum += $answers[$qNum];
                }
            }
            $scores[$name] = $sum;
        }
        
        return $scores;
    }

    public static function getSdqSubscaleClassification($subscale, $score)
    {
        switch ($subscale) {
            case 'emotional':
                if ($score <= 5) return ['Normal', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
                if ($score == 6) return ['Borderline', 'bg-amber-50 text-amber-700 border-amber-100'];
                return ['Abnormal', 'bg-rose-50 text-rose-700 border-rose-100'];
            case 'conduct':
                if ($score <= 3) return ['Normal', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
                if ($score == 4) return ['Borderline', 'bg-amber-50 text-amber-700 border-amber-100'];
                return ['Abnormal', 'bg-rose-50 text-rose-700 border-rose-100'];
            case 'hyperactivity':
                if ($score <= 5) return ['Normal', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
                if ($score == 6) return ['Borderline', 'bg-amber-50 text-amber-700 border-amber-100'];
                return ['Abnormal', 'bg-rose-50 text-rose-700 border-rose-100'];
            case 'peer':
                if ($score <= 3) return ['Normal', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
                if ($score == 4) return ['Borderline', 'bg-amber-50 text-amber-700 border-amber-100'];
                return ['Abnormal', 'bg-rose-50 text-rose-700 border-rose-100'];
            case 'prosocial':
                if ($score >= 6) return ['Normal', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
                if ($score == 5) return ['Borderline', 'bg-amber-50 text-amber-700 border-amber-100'];
                return ['Abnormal', 'bg-rose-50 text-rose-700 border-rose-100'];
        }
        return ['-', 'bg-slate-50 text-slate-700 border-slate-200'];
    }

    public static function getPsc17SubscaleClassification($subscale, $score)
    {
        switch ($subscale) {
            case 'internalizing':
                if ($score >= 5) return ['Positif (Berisiko)', 'bg-rose-50 text-rose-700 border-rose-100'];
                return ['Negatif (Normal)', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
            case 'attention':
                if ($score >= 7) return ['Positif (Berisiko)', 'bg-rose-50 text-rose-700 border-rose-100'];
                return ['Negatif (Normal)', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
            case 'externalizing':
                if ($score >= 7) return ['Positif (Berisiko)', 'bg-rose-50 text-rose-700 border-rose-100'];
                return ['Negatif (Normal)', 'bg-emerald-50 text-emerald-700 border-emerald-100'];
        }
        return ['-', 'bg-slate-50 text-slate-700 border-slate-200'];
    }
}