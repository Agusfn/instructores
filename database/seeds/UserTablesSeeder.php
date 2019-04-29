<?php

use Illuminate\Database\Seeder;

class UserTablesSeeder extends Seeder
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


        // Agregamos un usuario
        DB::table('users')->insert([
            'name' => 'José',
            'surname' => "López",
            'email' => 'usuario@mail.com',
            'password' => bcrypt('03488639268'),
            'email_verified_at' => '2019-04-25 00:00:00'
        ]);


        // Agregamos un instructor
        DB::table('instructors')->insert([
        	"name" => "Juan",
        	"surname" => "Gimenez",
        	"email" => "instructor@mail.com",
        	"password" => bcrypt('03488639268'),
        	"approved" => false,
        	"balance" => 0,
            'email_verified_at' => '2019-04-25 00:00:00'
        ]);


    }
}
