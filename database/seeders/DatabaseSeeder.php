<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use App\Models\Building;
use App\Models\Room;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\AcademicCalendar;
use App\Models\AcademicCalendarMeeting;
use App\Models\AcademicCalendarHoliday;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Seed Lecturer
        $lecturer = User::create([
            'name' => 'Dr. Lecturer',
            'email' => 'lecturer@example.com',
            'password' => Hash::make('password'),
            'role' => 'lecturer',
        ]);

        // Seed Students
        $students = [];
        for ($i = 1; $i <= 30; $i++) {
            $students[] = User::create([
                'name' => "Student $i",
                'email' => "student$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        }

        // Seed Profiles
        foreach ($students as $student) {
            Profile::create([
                'user_id' => $student->id,
                'nim' => 'NIM' . str_pad($student->id, 5, '0', STR_PAD_LEFT),
                'bio' => 'Bio of student ' . $student->name,
                'phone' => '08123456789',
                'address' => 'Address of student ' . $student->name,
                'face_images' => json_encode(array_fill(0, 30, 'path/to/face/image.jpg')),
            ]);
        }

        // Seed Buildings
        $buildings = [];
        for ($i = 1; $i <= 3; $i++) {
            $buildings[] = Building::create([
                'name' => "Gedung $i",
            ]);
        }

        // Seed Rooms
        $rooms = [];
        foreach ($buildings as $building) {
            for ($j = 1; $j <= 5; $j++) {
                $rooms[] = Room::create([
                    'name' => "Ruang {$building->name}-$j",
                    'building_id' => $building->id,
                    'device_id' => 'DEV-' . uniqid(),
                ]);
            }
        }

        // Seed Semesters
        $semesters = [
            Semester::create(['name' => 'Ganjil']),
            Semester::create(['name' => 'Genap']),
            Semester::create(['name' => 'Pendek']),
        ];

        // Seed Academic Years
        $currentYear = date('Y');
        $academicYears = [];
        foreach ($semesters as $semester) {
            $academicYears[] = AcademicYear::create([
                'name' => "$currentYear/" . ($currentYear + 1) . " - {$semester->name}",
                'start_date' => Carbon::create($currentYear, 8, 1),
                'end_date' => Carbon::create($currentYear + 1, 1, 31),
                'is_current' => ($semester->name === 'Ganjil'),
                'semester_id' => $semester->id,
            ]);
        }

        // Seed Courses
        $courses = [
            Course::create([
                'code' => 'CS101',
                'name' => 'Dasar Pemrograman',
                'capacity' => 40,
                'lecturer_id' => $lecturer->id,
            ]),
            Course::create([
                'code' => 'CS102',
                'name' => 'Struktur Data',
                'capacity' => 35,
                'lecturer_id' => $lecturer->id,
            ]),
            Course::create([
                'code' => 'CS201',
                'name' => 'Basis Data',
                'capacity' => 30,
                'lecturer_id' => $lecturer->id,
            ]),
        ];

        // Seed Enrollments
        foreach ($students as $student) {
            foreach ($courses as $course) {
                Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'academic_year_id' => $academicYears[0]->id,
                ]);
            }
        }

        // Seed Schedules
        $startDate = Carbon::now()->startOfWeek();
        foreach ($courses as $course) {
            for ($week = 1; $week <= 16; $week++) {
                Schedule::create([
                    'course_id' => $course->id,
                    'room_id' => $rooms[array_rand($rooms)]->id,
                    'start_time' => $startDate->copy()->addWeeks($week - 1)->setTime(8, 0),
                    'end_time' => $startDate->copy()->addWeeks($week - 1)->setTime(10, 0),
                ]);
            }
        }

        // Seed Academic Calendar
        $academicCalendar = AcademicCalendar::create([
            'name' => 'Kalender Akademik ' . $currentYear,
            'start_date' => Carbon::create($currentYear, 8, 1),
            'end_date' => Carbon::create($currentYear + 1, 6, 30),
            'academic_year_id' => $academicYears[0]->id,
            'semester_id' => $semesters[0]->id,
            'is_current' => true,
            'description' => 'Kalender akademik tahun ajaran ' . $currentYear,
        ]);

        // Seed Academic Calendar Meetings
        for ($meeting = 1; $meeting <= 16; $meeting++) {
            AcademicCalendarMeeting::create([
                'academic_calendar_id' => $academicCalendar->id,
                'meeting_number' => $meeting,
                'meeting_date' => $startDate->copy()->addWeeks($meeting - 1),
                'description' => "Pertemuan ke-$meeting",
            ]);
        }

        // Seed Academic Calendar Holidays
        $holidays = [
            ['date' => Carbon::create($currentYear, 8, 17), 'name' => 'HUT RI'],
            ['date' => Carbon::create($currentYear, 12, 25), 'name' => 'Natal'],
        ];
        
        foreach ($holidays as $holiday) {
            AcademicCalendarHoliday::create([
                'academic_calendar_id' => $academicCalendar->id,
                'holiday_date' => $holiday['date'],
                'name' => $holiday['name'],
                'description' => $holiday['name'] . ' ' . $currentYear,
            ]);
        }
    }
}