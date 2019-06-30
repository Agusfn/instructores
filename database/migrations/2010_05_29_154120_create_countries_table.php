<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('countries', function (Blueprint $table) {

            $table->string('code_alpha3',5);
            $table->integer('code_number');
            $table->string('name_english');
            $table->string('code_alpha2',5);
            $table->string('currency_code', 5);


            
            $table->primary('code_alpha3');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
