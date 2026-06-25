<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['team_id','titre','date','location'])]
class Training extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withPivot('present','comment')
            ->withTimestamps();
    }

    public function formatdate() {
        return  \Carbon\Carbon::parse($this->date)->translatedFormat('d F Y');
    }

}
