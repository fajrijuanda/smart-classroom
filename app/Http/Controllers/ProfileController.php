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


    public function manageFaceImages(Request $request)
    {
        $request->validate([
            'action' => 'required|in:add,remove,replace',
            'images' => 'required|array',
            'images.*' => 'url'
        ]);
    
        $user = $request->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $currentImages = $profile->face_images ? json_decode($profile->face_images, true) : [];
    
        switch ($request->action) {
            case 'add':
                $updatedImages = array_merge($currentImages, $request->images);
                break;
                
            case 'remove':
                $updatedImages = array_diff($currentImages, $request->images);
                break;
                
            case 'replace':
                $updatedImages = $request->images;
                break;
        }
    
        $profile->face_images = json_encode(array_values(array_unique($updatedImages)));
        $profile->save();
    
        return response()->json([
            'message' => 'Face images updated',
            'face_images' => $updatedImages
        ]);
    }
}