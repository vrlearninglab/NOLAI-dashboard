<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamSetting extends Model
{
    use HasFactory;
    protected $fillable = ['ip', 'port'];
}
