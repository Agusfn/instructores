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
            'name' => 'Vicente',
            'email' => 'admin@mail.com',
            'password' => bcrypt('instruc@catedral2209'),
        ]);

    }
}
