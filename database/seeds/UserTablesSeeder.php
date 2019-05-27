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
            'phone_number' => '+54 9 11 14725836',
            'profile_picture' => '1GU0SmY3kSL2MaUpjKCuwRrid8dUqSNfrwK2uNdC.jpeg',
            'instagram_username' => 'snowboarding',
            'identification_imgs' => '992487293.jpg',
            'professional_cert_imgs' => '1272570171.jpg,383333699.jpg',
            'documents_sent_at' => '2019-05-13 12:04:25',
        	"approved" => true,
            'approved_at' => '2019-05-13 12:05:21',
            'identification_type' => 'dni',
            'identification_number' => '12345678',
        	"balance" => 0,
            'email_verified_at' => '2019-04-25 00:00:00'
        ]);


        DB::table('instructor_services')->insert([

            "number" => 8617421,
            "published" => true,
            "instructor_id" => 1,

            "description" => "Soy Fulanito, tengo 26, vivo en Bariloche y hago snowboard desde los 10 años. Soy instructor desde hace 6 años.

Doy clases de todos los niveles.

Inicial: Clase introductoria, conceptos básicos, cómo ponerse y sacarse la tabla, cómo llevar la tabla en la telesilla, primera bajada.
Medio: Curvas, maniobras, caminos, perfeccionar slalom.
Alto: Trucos, carreras, competencias",

            "features" => "Clases para todas las edades
Clases introductorias
Clases para profesionales
Saltos y trucos de snowboard
Clases para discapacitados",

            "images_json" => '[{"name":"2002516588.jpg","thumbnail_name":"2002516588-thumbnail.jpg"},{"name":"414001443.jpg","thumbnail_name":"414001443-thumbnail.jpg"},{"name":"1216263095.jpg","thumbnail_name":"1216263095-thumbnail.jpg"},{"name":"497402672.jpg","thumbnail_name":"497402672-thumbnail.jpg"},{"name":"944462171.jpg","thumbnail_name":"944462171-thumbnail.jpg"}]',

            "worktime_hour_start" => 9,
            "worktime_hour_end" => 17

        ]);


        DB::table('service_date_ranges')->insert([
            "instructor_service_id" => 1,
            "date_start" => "2019-06-15",
            "date_end" => "2019-06-30",
            "price_per_block" => 2000.00
        ]);
        DB::table('service_date_ranges')->insert([
            "instructor_service_id" => 1,
            "date_start" => "2019-07-02",
            "date_end" => "2019-07-15",
            "price_per_block" => 3500.00
        ]);
        DB::table('service_date_ranges')->insert([
            "instructor_service_id" => 1,
            "date_start" => "2019-07-16",
            "date_end" => "2019-07-30",
            "price_per_block" => 3200.00
        ]);    
        DB::table('service_date_ranges')->insert([
            "instructor_service_id" => 1,
            "date_start" => "2019-08-02",
            "date_end" => "2019-08-15",
            "price_per_block" => 2800.00
        ]);
        DB::table('service_date_ranges')->insert([
            "instructor_service_id" => 1,
            "date_start" => "2019-08-20",
            "date_end" => "2019-08-30",
            "price_per_block" => 2500.00
        ]);


        DB::table('reservations')->insert([
            "code" => "3A3ZY2BV2N5",
            "user_id" => 1,
            "instructor_service_id" => 1,
            "status" => "payment-pending",
            "reserved_date" => "2019-08-15",
            "reserved_time_start" => 9,
            "reserved_time_end" => 11,
            "persons_ammount" => 1,
            "instructor_pay" => 2000,
            "service_fee" => 400,
            "final_price" => 2400,
            "payment_proc_fee" => 100,
            "instructor_movement_id" => null
        ]);

        DB::table('reservations')->insert([
            "code" => "HFA5WKXTYAC",
            "user_id" => 1,
            "instructor_service_id" => 1,
            "status" => "pending-confirmation",
            "reserved_date" => "2019-08-10",
            "reserved_time_start" => 9,
            "reserved_time_end" => 15,
            "persons_ammount" => 1,
            "instructor_pay" => 4000,
            "service_fee" => 400,
            "final_price" => 4400,
            "payment_proc_fee" => 200,
            "instructor_movement_id" => null
        ]);


        DB::table('reservations')->insert([
            "code" => "FT0GB5XT2G0",
            "user_id" => 1,
            "instructor_service_id" => 1,
            "status" => "confirmed",
            "reserved_date" => "2019-07-18",
            "reserved_time_start" => 11,
            "reserved_time_end" => 15,
            "persons_ammount" => 1,
            "instructor_pay" => 1500,
            "service_fee" => 400,
            "final_price" => 1900,
            "payment_proc_fee" => 120,
            "instructor_movement_id" => null
        ]);



        DB::table('reservations')->insert([
            "code" => "LJ60FFWZ0RP",
            "user_id" => 1,
            "instructor_service_id" => 1,
            "status" => "confirmed",
            "reserved_date" => "2019-07-15",
            "reserved_time_start" => 11,
            "reserved_time_end" => 15,
            "persons_ammount" => 1,
            "instructor_pay" => 1500,
            "service_fee" => 400,
            "final_price" => 1900,
            "payment_proc_fee" => 120,
            "instructor_movement_id" => null
        ]);


        DB::table('instructor_balance_movements')->insert([
            "instructor_id" => 1,
            "motive" => "payment",
            "collection_id" => null,
            "reservation_id" => 1,
            "date" => "2019-05-20 12:38:00",
            "net_ammount" => 2000.80,
            "created_at" => "2019-05-20 12:38:00",
            "updated_at" => "2019-05-20 12:38:00"
        ]);

        DB::table('instructor_balance_movements')->insert([
            "instructor_id" => 1,
            "motive" => "collection",
            "collection_id" => 1,
            "reservation_id" => null,
            "date" => "2019-05-21 13:00:00",
            "net_ammount" => -542.20,
            "created_at" => "2019-05-21 13:00:00",
            "updated_at" => "2019-05-21 13:00:00"
        ]);


    }
}
