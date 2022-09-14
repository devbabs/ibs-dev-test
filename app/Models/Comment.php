<?php

namespace App\Models;

use App\Traits\UuidScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use UuidScopeTrait;

    use HasFactory;

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
