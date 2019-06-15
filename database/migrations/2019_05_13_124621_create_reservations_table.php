<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('instructor_id');
            $table->unsignedBigInteger('instructor_service_id');
            $table->string('status');
            $table->string('sport_discipline');
            $table->date('reserved_class_date');
            $table->integer('reserved_time_start');
            $table->integer('reserved_time_end');
            $table->integer('time_blocks_amount');
            $table->decimal('price_per_block', 8, 2);
            $table->integer('adults_amount');
            $table->integer('kids_amount');
            $table->text('json_breakdown');
            $table->decimal('final_price', 8, 2);
            $table->decimal('instructor_pay', 8, 2);
            $table->decimal('service_fee', 8, 2);
            $table->decimal('payment_proc_fee', 8, 2)->nullable();
            $table->unsignedBigInteger('instructor_wallet_movement_id')->nullable(); // constraint is made when wallet movements table is done
            $table->text('confirm_message')->nullable();
            $table->text('reject_message')->nullable();

            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('instructor_id')->references('id')->on('instructors');
            $table->foreign('instructor_service_id')->references('id')->on('instructor_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
