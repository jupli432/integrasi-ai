<?php

namespace App\Models;

use App\Helper\UUIDV4;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory, UUIDV4;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name'
    ];
}
