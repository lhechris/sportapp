<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameOption extends Model
{
    protected $fillable = ['name', 'team_id','display','type','order'];

    const TYPE_TEXT="text";
    const TYPE_CHECKBOX = "checkbox";
    const TYPE_OPPOSITION = "opposition";
    const TYPE_NUM = "num";

    const DISP_ALL = "all";
    const DISP_COACH = "coach";
    const DISP_STAT = "stat";
    const DISP_ALL_EDITABLE = "all_editable";

    public function values()
    {
        return $this->hasMany(GameMemberOption::class, 'game_option_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
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

    //Helpers
    public static function types(): array
    {
        return [
            self::TYPE_TEXT     => __('sportapp.text'),
            self::TYPE_CHECKBOX => __('sportapp.checkbox'),
            self::TYPE_OPPOSITION => __('sportapp.opposition'),
            self::TYPE_NUM      => __('sportapp.number'),
        ];
    }

    public static function displays(): array
    {
        return [
            self::DISP_ALL   => __('sportapp.all'),
            self::DISP_ALL_EDITABLE   => __('sportapp.all_updatable'),
            self::DISP_COACH => __('sportapp.coach'),
            self::DISP_STAT      => __('sportapp.statistics'),
        ];
    }

    public function isDisplayTable() :bool {
        return (($this->display === self::DISP_ALL) || 
                ($this->display === self::DISP_ALL_EDITABLE) ||
                ($this->display === self::DISP_COACH));
    }

    public function isDisplayTableCoach() :bool {
        return ($this->display === self::DISP_COACH);
    }

    public function isDisplayTableAllEditable() :bool {
        return (($this->display === self::DISP_ALL_EDITABLE) ||
                ($this->display === self::DISP_COACH));
    }

    public function isDisplayStat() :bool {
        return ($this->display === self::DISP_STAT);
    }

}
