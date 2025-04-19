<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileCv2 extends Model
{
    use HasFactory;

    protected $table = 'profile_cvs';
    
    protected $fillable = [
        'user_id',
        'title',
        'cv_file',
        'is_default',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Education via User (hasManyThrough)
    public function educations()
    {
        return $this->hasManyThrough(
            ProfileEducation::class,
            User::class,
            'id',       // Foreign key di tabel users (user.id)
            'user_id',  // Foreign key di tabel profile_educations (profile_educations.user_id)
            'user_id',  // Local key di tabel profile_cvs (profile_cvs.user_id)
            'id'        // Local key di tabel users (users.id)
        );
    }

    
    public function experiences()
{
    return $this->hasManyThrough(
        ProfileExperience::class,
        User::class,
        'id',       // Foreign key di tabel users (users.id)
        'user_id',  // Foreign key di tabel profile_experiences (profile_experiences.user_id)
        'user_id',  // Local key di tabel profile_cvs (profile_cvs.user_id)
        'id'        // Local key di tabel users (users.id)
    );
}

    // Relasi ke Skill
    public function skills()
    {
        return $this->hasMany(ProfileSkill::class, 'user_id');
    }
}