<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ProfileCv2;



class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke ProfileCv2
    public function profileCv()
    {
        return $this->hasOne(\App\Models\ProfileCv2::class, 'user_id');
    }

    // Relasi ke educations melalui ProfileCv2
    public function educations()
    {
        return $this->hasManyThrough(
            ProfileEducation::class,
            ProfileCv2::class,
            'user_id', // Foreign key pada tabel profile_cvs
            'user_id' // Foreign key pada tabel profile_educations
        );
    }

    // Relasi ke experiences melalui ProfileCv2
    public function experiences()
    {
        return $this->hasManyThrough(
            ProfileExperience::class,
            ProfileCv2::class,
            'user_id', // Foreign key pada tabel profile_cvs
            'user_id' // Foreign key pada tabel profile_experiences
        );
    }

    // Relasi ke skills melalui ProfileCv2
    public function skills()
    {
        return $this->hasManyThrough(
            ProfileSkill::class,
            ProfileCv2::class,
            'user_id', // Foreign key pada tabel profile_cvs
            'user_id' // Foreign key pada tabel profile_skills
        );
    }
}