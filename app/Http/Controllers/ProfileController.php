<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        $request->validate([
            'nim' => 'sometimes|required|string|max:20',
            'bio' => 'nullable|string|max:255',
            'avatar' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'face_images' => 'nullable|array',
        ]);

        if ($profile) {
            $profile->update($request->all());
        } else {
            $profile = $user->profile()->create($request->all());
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile
        ]);
    }

    public function getFaceImages(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile || !$profile->face_images) {
            return response()->json(['face_images' => []]);
        }

        return response()->json([
            'face_images' => json_decode($profile->face_images, true)
        ]);
    }
}