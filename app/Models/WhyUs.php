<?php

namespace App\Models;

use App\Helper\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhyUs extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'uuid','title','content','urut'
    ];
}
