<?php

use App\Instructor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Demo-only accounts with conventional (email + password) login and verified emails:
 * a regular user and an approved instructor.
 * Not called from DatabaseSeeder on purpose: it is run only by the Docker entrypoint.
 */
class DemoAccountsSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Demo',
            'surname' => 'User',
            'email' => 'user@demo.test',
            'password' => Hash::make('demo1234'),
            'email_verified_at' => now(),
            'phone_number' => '11 1234 5678',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $instructor = Instructor::create([
            'name' => 'Demo',
            'surname' => 'Instructor',
            'email' => 'instructor@demo.test',
            'password' => Hash::make('demo1234'),
            'email_verified_at' => now(),
            'phone_number' => '11 8765 4321',
            'approved' => false,
        ]);

        // Same path as the admin approval: creates the InstructorService and InstructorWallet.
        $instructor->approve('dni', '12345678', 3);
    }
}
