<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function detect()
    {
        $studentId = 1; // Simulasi hasil face recognition
        // $student = Student::find($studentId);

        // Example
        return response()->json([
            'name' => 'Bisma Candra Gumilang',
            'nim' => '22416255201165',
            'class' => 'IF22B',
            'last_seen' => now()->subDays(3)->format('d M Y H:i'),
        ]);

        /*if ($student) {
            $now = now();
            $student->presences()->create(['datetime' => $now]);

            return response()->json([
                'name' => $student->name,
                'nim' => $student->nim,
                'class' => $student->class,
                'last_seen' => $now->format('d M Y H:i'),
            ]);
        }*/

        return response()->json(['name' => null]);
    }
}
