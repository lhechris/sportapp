<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['email','token','created_by','expires_at','used','user_id'])]
class Invitation extends Model
{
        public function user()
    {
        return $this->belongsTo(User::class);
    }
}
