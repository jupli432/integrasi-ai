<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorSubject extends Model
{
    protected $table = 'major_subjects';

    protected $fillable = [
        'major_subject_id', // Jika kolom ini ada di tabel (pastikan ini bukan typo, atau mungkin seharusnya 'id')
        'major_subject',
        'is_default',
        'is_active',
        'sort_order',
        'lang',
    ];

    // Relasi ke ProfileEducation melalui tabel pivot
    public function profileEducations()
    {
        return $this->belongsToMany(
            ProfileEducation::class,
            'profile_education_major_subjects',
            'major_subject_id',  // Kolom di tabel pivot yang merujuk ke MajorSubject
            'profile_education_id' // Kolom di tabel pivot yang merujuk ke ProfileEducation
        );
    }
}