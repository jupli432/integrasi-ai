<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    protected $table = 'job_skills'; // Sesuaikan dengan nama tabel
    
    protected $fillable = [
        'job_skill_id',
        'job_skill', 
        'is_default',
        'is_active',
        'sort_order',
        'lang'
    ];

    // Relasi ke ProfileSkill
    public function profileSkills()
    {
        return $this->hasMany(ProfileSkill::class, 'job_skill_id');
    }

    // Aksesor untuk nama skill
    public function getNameAttribute()
    {
        return $this->job_skill;
    }
}