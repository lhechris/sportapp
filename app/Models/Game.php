<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    protected $fillable = ['team_id', 'date', 'location','horaire','rendezvous'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withPivot('availability', 'selected')
            ->withTimestamps();
    }

}
