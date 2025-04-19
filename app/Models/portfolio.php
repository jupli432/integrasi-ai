<?php

namespace App\Models;

use App\Models\shortLink;
use App\Helper\GeneratorID;
use App\Models\portfolioDetail;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class portfolio extends Model
{
    use HasFactory, Sluggable, GeneratorID;
    
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'appsName',
        'slug',
        'appsType',
        'filter',
        'pathImage',
        'description',
        'client',
        'projectDate',
        'projectUrl',
        'documentationLink',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'appsName'
            ]
        ];
    }

    public function portfolioDetail()
    {
        return $this->hasMany(portfolioDetail::class,'portfolios_id','id');
    }

    public function project_url()
    {
        return $this->hasOne(shortLink::class,'randomStr','projectUrl')->select('randomStr','destinationLink');
    }

    public function documentation_link()
    {
        return $this->hasOne(shortLink::class,'randomStr','documentationLink')->select('randomStr','destinationLink');
    }
}
