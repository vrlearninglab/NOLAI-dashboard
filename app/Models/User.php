<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
