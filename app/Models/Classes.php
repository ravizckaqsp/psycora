<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name', 'grade', 'academic_year'];

    public function students()
    {
        return $this->hasManyThrough(
            User::class,
            Siswa::class,
            'class_id', // Foreign key on Siswa table
            'id',       // Foreign key on User table (target)
            'id',       // Local key on Classes table
            'user_id'   // Local key on Siswa table
        );
    }
}
