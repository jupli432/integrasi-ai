<?php

namespace App\Models;

use App\Helper\GeneratorID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ourServices extends Model
{
    use HasFactory, GeneratorID;

    public $incrementing = false;
    protected $fillable = [
        'profile_user_id',
        'name',
        'icon',
        'description',
        'urut'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','profile_user_id')->select('id','name');
    }
}
