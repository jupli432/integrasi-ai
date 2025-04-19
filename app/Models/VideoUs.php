<?php

namespace App\Models;


use App\Helper\UUIDV4;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoUs extends Model
{
    use HasFactory, UUIDV4;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    
    protected $fillable = [
        'title','link_embed','description'
    ];
}
