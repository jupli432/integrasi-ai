<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Skill;

class ProfileSkill extends Model
{
    protected $table = 'profile_skills';
    
    protected $fillable = [
        'user_id', 
        'job_skill_id', 
        'job_experience_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profileCv2()
    {
        return $this->belongsTo(ProfileCv2::class, 'user_id');
    }
    public function jobSkill()
    {
        return $this->belongsTo(JobSkill::class, 'job_skill_id');
    }
}