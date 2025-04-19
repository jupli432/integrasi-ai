<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileExperience extends Model
{
    protected $table = 'profile_experiences';
    
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'country_id',
        'state_id',
        'city_id',
        'date_start',
        'date_end',
        'is_currently_working',
        'description'
    ];

    public function profileCv2()
    {
        return $this->belongsTo(ProfileCv2::class, 'user_id');
    }
}