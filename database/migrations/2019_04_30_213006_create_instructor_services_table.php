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
            $table->integer('instructor_level');
            $table->boolean('snowboard_discipline')->nullable();
            $table->boolean('ski_discipline')->nullable();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->text('images_json')->nullable();
            $table->integer('worktime_hour_start');
            $table->integer('worktime_hour_end');
            $table->integer('worktime_alt_hour_start')->nullable();
            $table->integer('worktime_alt_hour_end')->nullable();
            $table->boolean('offered_to_adults')->nullable();
            $table->boolean('offered_to_kids')->nullable();
            $table->boolean('allows_groups')->default(false);
            $table->integer('max_group_size')->default(6);
            $table->decimal('person2_discount', 4, 2)->default(0);
            $table->decimal('person3_discount', 4, 2)->default(0);
            $table->decimal('person4_discount', 4, 2)->default(0);
            $table->decimal('person5_discount', 4, 2)->default(0);
            $table->decimal('person6_discount', 4, 2)->default(0);
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
