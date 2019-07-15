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

            $table->dropColumn('person2_discount');
            $table->dropColumn('person3_discount');
            $table->dropColumn('person4_discount');
            $table->dropColumn('person5_discount');
            $table->dropColumn('person6_discount');

            $table->decimal('person2_surcharge', 5, 2)->default(0);
            $table->decimal('person3_surcharge', 5, 2)->default(0);
            $table->decimal('person4_surcharge', 5, 2)->default(0);
            $table->decimal('person5_surcharge', 5, 2)->default(0);
            $table->decimal('person6_surcharge', 5, 2)->default(0);

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

            $table->dropColumn('person2_surcharge');
            $table->dropColumn('person3_surcharge');
            $table->dropColumn('person4_surcharge');
            $table->dropColumn('person5_surcharge');
            $table->dropColumn('person6_surcharge');
            
            $table->decimal('person2_discount', 5, 2)->default(0);
            $table->decimal('person3_discount', 5, 2)->default(0);
            $table->decimal('person4_discount', 5, 2)->default(0);
            $table->decimal('person5_discount', 5, 2)->default(0);
            $table->decimal('person6_discount', 5, 2)->default(0);

        });

    }
}
