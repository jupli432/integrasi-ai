<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileEducation extends Model
{
    protected $table = 'profile_educations';
    
    protected $fillable = [
        'user_id',
        'degree_level_id',
        'degree_type_id',
        'degree_title',
        'country_id',
        'state_id',
        'city_id',
        'date_completion',
        'institution',
        'degree_result',
        'result_type_id'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke MajorSubject melalui tabel pivot
    public function majorSubjects()
    {
        return $this->belongsToMany(
            MajorSubject::class,
            'profile_education_major_subjects',
            'profile_education_id', // Kolom di tabel pivot yang merujuk ke ProfileEducation
            'major_subject_id'     // Kolom di tabel pivot yang merujuk ke MajorSubject
        );
    }
}