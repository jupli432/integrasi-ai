<?php

namespace App\Models;

use App\Traits\JobTrait;  // Menggunakan Trait yang sudah Anda buat
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMo extends Model
{
    use HasFactory, JobTrait;  // Menggunakan Trait dalam Model

    // Nama tabel yang terkait dengan model ini (jika tabelnya bukan default)
    protected $table = 'jobs';

    // Kolom-kolom yang dapat diisi (mass-assignment)
    protected $fillable = [
        'company_id',
        'title',
        'assignment',
       
    ];

    // // Relasi dengan model User (sebuah ProfileCv dimiliki oleh seorang User)
    // public function user()
    // {
    //     return $this->belongsTo(User::class); // Menghubungkan dengan model User
    // }
}
