<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placeholder extends Model
{
    use HasFactory;

    protected $table = 'placeholder';
    protected $fillable = ['dummy_data'];
    public $timestamps = true;
}