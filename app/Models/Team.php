<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name','owner_id'])]
class Team extends Model
{
    public function owner() {
        return $this->hasMany(User::class, 'owner_id');
    }
    
    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    // helpers utiles
    public function players()
    {
        return $this->members()->wherePivot('role', 'player');
    }

    public function coaches()
    {
        return $this->members()->wherePivot('role', 'coach');
    }

    
    public function games()
    {
        return $this->hasMany(\App\Models\Game::class);
    }

}
