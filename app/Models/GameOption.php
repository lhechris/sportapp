<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameOption extends Model
{
    protected $fillable = ['name', 'label'];

    public function values()
    {
        return $this->hasMany(GameMemberOption::class, 'game_option_id');
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_member_option', 'game_option_id', 'game_id')
            ->withPivot('member_id', 'value')
            ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'game_member_option', 'game_option_id', 'member_id')
            ->withPivot('game_id', 'value')
            ->withTimestamps();
    }
}
