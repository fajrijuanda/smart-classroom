<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function create()
    {
        // Menampilkan halaman Vue untuk melengkapi profil
        return Inertia::render('profile/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:255|unique:profiles,nim',
            'phone' => 'required|string|max:20',
            'images' => 'required|array|size:30', // Validasi harus ada 30 gambar
            'images.*' => 'required|string',
        ]);

        $user = $request->user();
        $faceImagePaths = [];

        // Loop dan simpan setiap gambar ke storage
        foreach ($validated['images'] as $imageData) {
            // Decode Base64
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageData));
            // Buat nama file unik
            $filename = uniqid('face_') . '.jpg';
            // Tentukan path penyimpanan
            $path = "public/face_data/{$user->id}/{$filename}";

            // Simpan file
            Storage::put($path, $image);

            // Simpan path yang dapat diakses publik untuk disimpan di DB
            $faceImagePaths[] = Storage::url($path);
        }

        // Buat record profil dengan path gambar dalam format JSON
        Profile::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'phone' => $validated['phone'],
            'face_images' => json_encode($faceImagePaths), // Simpan array of paths
            // Isi field lain jika ada (bio, address, dll.)
        ]);

        return to_route('dashboard')->with('success', 'Profile created successfully!');
    }
}