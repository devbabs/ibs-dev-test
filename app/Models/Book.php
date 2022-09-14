<?php

namespace App\Models;

use App\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use UuidScopeTrait;

    use HasFactory;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
