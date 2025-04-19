<?php

namespace App\Models;

use App\Helper\GeneratorID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class contactUs extends Model
{
    use HasFactory, GeneratorID;
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     * */
    protected $fillable = [
        'id',
        'our_services',
        'full_name',
        'email',
        'phone_number',
        'message',
        'status'
    ];
}
