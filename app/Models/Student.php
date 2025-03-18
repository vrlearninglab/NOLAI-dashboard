<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Geef de tabelnaam expliciet aan (optioneel)
    protected $table = 'students';

    // Geef de velden aan die massaal ingevuld mogen worden
    protected $fillable = ['student_nummer'];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}