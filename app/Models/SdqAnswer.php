<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdqAnswer extends Model
{
    protected $fillable = ['response_id', 'question_number', 'answer_value'];

    public function response()
    {
        return $this->belongsTo(QuestionnaireResponse::class, 'response_id');
    }
}