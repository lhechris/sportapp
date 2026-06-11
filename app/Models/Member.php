<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    
    const TYPE_PLAYER = 'player';
    const TYPE_COACH = 'coach';
    const TYPE_STAFF = 'staff';

    protected $fillable = [
        'name',
        'prenom',
        'type',
        'user_id',
        'birthdate',
        'licence'
    ];

    // lien éventuel vers un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('relation')
            ->withTimestamps();
    }


    public function parents()
    {
        return $this->users()->wherePivot('relation', 'parent');
    }

    
    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->withPivot('role')
            ->withTimestamps();
    }


    public function games()
    {
        return $this->belongsToMany(Game::class)
            ->withPivot('availability', 'selected')
            ->withTimestamps();
    }

}
