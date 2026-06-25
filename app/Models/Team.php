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
            ->withTimestamps();
    }

    public function games()
    {
        return $this->hasMany(\App\Models\Game::class)
                ->orderBy('date');
    }

    public function trainings()
    {
        return $this->hasMany(\App\Models\Training::class)
                ->orderBy('date');
    }

    // helpers utiles
    public function players()
    {
        return $this->members()->where('type', Member::TYPE_PLAYER);
    }

    public function coaches()
    {
        return $this->members()->where('type',Member::TYPE_COACH);
    }

    public function staffs()
    {
        return $this->members()->where('type', Member::TYPE_STAFF);
    }
}
