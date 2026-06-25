<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMemberOption extends Model
{
    protected $table = 'game_member_option';

    protected $fillable = [
        'game_id',
        'member_id',
        'game_option_id',
        'value',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function option()
    {
        return $this->belongsTo(GameOption::class, 'game_option_id');
    }
}
