<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskClassification extends Model
{
    protected $fillable = [
        'response_id', 'sdq_category', 'psc17_category',
        'sassv_category', 'overall_risk', 'recommendation',
    ];

    public function response()
    {
        return $this->belongsTo(QuestionnaireResponse::class, 'response_id');
    }
}