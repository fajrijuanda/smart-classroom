<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 1. Pastikan Auth diimpor
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => !$request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',

            // 2. Tambahkan logika navigasi dinamis di sini
            'nav' => function () {
                $user = Auth::user();
                if (!$user) {
                    return [];
                }

                 // --- TAMBAHKAN BARIS INI ---
                /** @var \App\Models\User $user */
                // --------------------------

                // Menu dasar untuk semua peran
                $menu = [
                    ['title' => 'Dashboard', 'href' => '/dashboard', 'icon' => 'LayoutGrid'],
                    ['title' => 'Courses', 'href' => '/courses', 'icon' => 'BookOpen'],
                    ['title' => 'Attendance', 'href' => '/attendance', 'icon' => 'ClipboardCheck'],
                ];

                // Tambahkan menu jika pengguna adalah admin
                if ($user->isAdmin()) {
                    $adminMenu = [
                        ['title' => 'Classrooms', 'href' => '/classrooms', 'icon' => 'DoorOpen'],
                        ['title' => 'Schedules', 'href' => '/schedules', 'icon' => 'CalendarDays'],
                    ];
                    $menu = array_merge($menu, $adminMenu);
                }

                return $menu;
            },
        ];
    }
}