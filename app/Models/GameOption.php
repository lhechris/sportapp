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

    const DISP_TAB_ALL = "tab_all";
    const DISP_TAB_COACH = "tab_coach";
    const DISP_STAT = "stat";

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
            self::TYPE_TEXT     => __('Text'),
            self::TYPE_CHECKBOX => __('Checkbox'),
            self::TYPE_OPPOSITION => __('Opposition'),
            self::TYPE_NUM      => __('Number'),
        ];
    }

    public static function displays(): array
    {
        return [
            self::DISP_TAB_ALL   => __('Table (all)'),
            self::DISP_TAB_COACH => __('Table (coach)'),
            self::DISP_STAT      => __('Statistic'),
        ];
    }

    public function isDisplayTable() :bool {
        return (($this->display === self::DISP_TAB_ALL) || ($this->display === self::DISP_TAB_COACH));
    }

    public function isDisplayTableAll() :bool {
        return ($this->display === self::DISP_TAB_COACH);
    }


    public function isDisplayStat() :bool {
        return ($this->display === self::DISP_STAT);
    }

}
