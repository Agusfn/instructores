<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Agregamos admin user
        DB::table('admins')->insert([
            'name' => 'Agustin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('03488639268'),
        ]);

    }
}
