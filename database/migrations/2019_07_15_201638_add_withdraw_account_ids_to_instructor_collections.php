<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithdrawAccountIdsToInstructorCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructor_collections', function($table) {

            $table->string("destination_acc_type");
            $table->unsignedBigInteger("instructor_bank_acc_id")->nullable();
            $table->unsignedBigInteger("instructor_mp_acc_id")->nullable();

            $table->foreign('instructor_bank_acc_id')->references('id')->on('instructor_bank_accounts');
            $table->foreign('instructor_mp_acc_id')->references('id')->on('instructor_mp_accounts');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructor_collections', function (Blueprint $table) {
                
            $table->dropForeign('instructor_collections_instructor_bank_acc_id_foreign');
            $table->dropForeign('instructor_collections_instructor_mp_acc_id_foreign');

            $table->dropColumn('destination_acc_type');
            $table->dropColumn('instructor_bank_acc_id');
            $table->dropColumn('instructor_mp_acc_id');

        });
    }
}
