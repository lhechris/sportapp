<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_COACH = 'coach';
    const ROLE_PLAYER = 'player';
    const ROLE_PARENT = 'parent';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function teams() {
        
        return $this->belongsToMany(Team::class)
            ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withPivot('relation')
            ->withTimestamps();
    }
    

    public function children()
    {
        return $this->members()->wherePivot('relation', 'parent');
    }



    public function isCoach(): bool
    {
        return $this->role === self::ROLE_COACH;
    }

    public function isPlayer(): bool
    {
        return $this->role === self::ROLE_PLAYER;
    }

    public function isParent(): bool
    {
        return $this->role === self::ROLE_PARENT;
    }

}
