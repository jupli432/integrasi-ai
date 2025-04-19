<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class OurTeamSkill extends Pivot
{
    use HasFactory;
    public $incrementing    = false;
    protected $keyType      = 'string';
    public $timestamps      = true;

    /**
     * Boot the model.
     *
     * Attach to the "creating" Model Event to provide a UUID value for the `id` column.
     * This is so that the pivot table can be used like a normal UUID-keyed model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
