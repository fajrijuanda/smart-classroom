<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function detect()
    {
        return response()->json([
            'name' => 'Bisma Candra Gumilang',
            'nim' => '22416255201165',
            'class' => 'IF22B',
            'last_seen' => now()->subDays(3)->format('d M Y H:i'),
        ]);
    }
}
