<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceAvailableDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_available_dates', function (Blueprint $table) {
            
            $table->unsignedBigInteger('instructor_service_id');
            $table->date('date');

            //$table->foreign('instructor_service_id')->references('id')->on('instructor_services');
            $table->primary(['instructor_service_id', 'date']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_available_dates');
    }
}
