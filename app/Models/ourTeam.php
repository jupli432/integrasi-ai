<?php

namespace App\Models;

use App\Models\Skill;
use App\Helper\GeneratorID;
use App\Models\OurTeamSkill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ourTeam extends Model
{
    use HasFactory, GeneratorID;

    public $incrementing = false;
    protected $fillable = [
        'profile_user_id',
        'name',
        'position',
        'urut',
        'status',
        'pathImage',
        'description',
        'twitterAccount',
        'facebookAccount',
        'instagramAccount',
        'linkedinAccount',
    ];

    /**
     * Many to Many relationship between ourTeam and Skill, using the our_team_skill pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'our_team_skills', 'id_our_team', 'id_skill')
                    ->using(OurTeamSkill::class)->withTimestamps();
    }

}
