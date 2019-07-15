<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeServiceGroupDiscountsToGroupSurcharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('instructor_services', function($table) {

            $table->renameColumn('person2_discount', 'person2_surcharge');
            $table->renameColumn('person3_discount', 'person3_surcharge');
            $table->renameColumn('person4_discount', 'person4_surcharge');
            $table->renameColumn('person5_discount', 'person5_surcharge');
            $table->renameColumn('person6_discount', 'person6_surcharge');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructor_services', function($table) {
            
            $table->renameColumn('person2_surcharge', 'person2_discount');
            $table->renameColumn('person3_surcharge', 'person3_discount');
            $table->renameColumn('person4_surcharge', 'person4_discount');
            $table->renameColumn('person5_surcharge', 'person5_discount');
            $table->renameColumn('person6_surcharge', 'person6_discount');

        });

    }
}
