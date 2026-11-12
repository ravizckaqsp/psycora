<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['type', 'number', 'text', 'subscale', 'reverse_scored'];

    protected $casts = ['reverse_scored' => 'boolean'];
}
