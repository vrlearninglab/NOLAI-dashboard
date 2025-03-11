<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Maak een aantal studenten aan (je kunt dit aantal aanpassen naar wens)
        $studentNumbers = [
            'S123456',
            'S234567',
            'S345678',
            'S456789',
            'S567890',
            'S678901',
            'S789012',
            'S890123',
            'S901234',
            'S012345'
        ];

        // Voeg elk studentnummer toe aan de database
        foreach ($studentNumbers as $studentNumber) {
            Student::create([
                'student_nummer' => $studentNumber,
            ]);
        }
    }
}
