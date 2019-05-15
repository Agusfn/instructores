<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('number');
            $table->boolean('published')->default(false);
            $table->unsignedBigInteger('instructor_id');
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->text('images_json')->nullable();
            $table->integer('worktime_hour_start');
            $table->integer('worktime_hour_end');
            $table->integer('worktime_alt_hour_start')->nullable();
            $table->integer('worktime_alt_hour_end')->nullable();
            $table->boolean('allows_groups')->default(true);
            $table->text('booking_calendar_json')->nullable();
            $table->timestamps();

            
            $table->foreign('instructor_id')->references('id')->on('instructors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructor_services');
    }
}
