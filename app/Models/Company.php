<?php

// app/Models/Company.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Company extends Model
{
    protected $fillable = [
        'name', 'email', 'ceo', 'industry_id', 'ownership_type_id', 'description', 'location', 
        'no_of_offices', 'website', 'no_of_employees', 'established_in', 'fax', 'phone', 'logo',
        'country_id', 'state_id', 'city_id', 'slug', 'is_active', 'is_featured', 'verified', 
        'verification_token', 'password', 'remember_token', 'map', 'created_at', 'updated_at',
        'facebook', 'twitter', 'linkedin', 'google_plus', 'pinterest', 'package_id',
        'package_start_date', 'package_end_date', 'jobs_quota', 'availed_jobs_quota', 
        'is_subscribed', 'cvs_package_id', 'cvs_package_start_date', 'cvs_package_end_date', 
        'cvs_quota', 'availed_cvs_quota', 'availed_cvs_ids', 'email_verified_at', 'payment_method', 
        'type', 'count'
    ];

    /**
     * Relasi dengan model Package (untuk paket CV)
     */
    public function cvPackage()
    {
        return $this->belongsTo(Package::class, 'cvs_package_id');
    }

    /**
     * Validasi apakah paket CV yang dimiliki perusahaan masih berlaku.
     *
     * @return bool
     */
    public function isValidCvPackage()
{
    return !$this->isExpired() && $this->hasQuotaAvailable();
}

public function isExpired()
{
    return Carbon::parse($this->end_date)->isPast();  // Cek jika paket sudah expired
}

public function hasQuotaAvailable()
{
    return $this->availed_cvs_quota < $this->cvs_quota;  // Pastikan kuota masih tersedia
}
}
