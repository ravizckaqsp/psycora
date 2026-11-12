<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'nip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    public function studentClass()
    {
        return $this->hasOneThrough(
            Classes::class,
            Siswa::class,
            'user_id',    // Foreign key on siswa table
            'id',         // Foreign key on classes table
            'id',         // Local key on users table
            'class_id'    // Local key on siswa table
        );
    }

    public function getNisAttribute()
    {
        return $this->siswa ? $this->siswa->nis : null;
    }

    public function questionnaireResponses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    public function chatbotLogs()
    {
        return $this->hasMany(ChatbotLog::class);
    }
}