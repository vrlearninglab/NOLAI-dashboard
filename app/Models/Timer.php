<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;

    protected $fillable = ['full_time', 'session_id'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}