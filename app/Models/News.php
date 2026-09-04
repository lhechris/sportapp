<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title','description','active'])]
class News extends Model
{
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
