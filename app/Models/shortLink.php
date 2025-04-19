<?php

namespace App\Models;

use App\Helper\GeneratorID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shortLink extends Model
{
    use HasFactory, GeneratorID;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'randomStr',
        'destinationLink',
        'expiredDay',
    ];
}
