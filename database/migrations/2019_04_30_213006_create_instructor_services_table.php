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
            $table->boolean('published');
            $table->unsignedBigInteger('instructor_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('pictures')->nullable();
            $table->integer('work_hour_start')->nullable();
            $table->integer('work_hour_end')->nullable();
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
