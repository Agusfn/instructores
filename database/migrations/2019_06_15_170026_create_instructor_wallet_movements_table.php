<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorWalletMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_wallet_movements', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('instructor_wallet_id');
            $table->string('motive');
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->dateTime('date');
            $table->decimal('net_amount', 8, 2);
            $table->decimal('new_balance', 8, 2);

            $table->foreign('instructor_wallet_id')->references('id')->on('instructor_wallets');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });


        Schema::table('reservations', function (Blueprint $table) {
            $table->foreign('instructor_wallet_movement_id')->references('id')->on('instructor_wallet_movements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign('reservations_instructor_wallet_movement_id_foreign');
        });

        Schema::dropIfExists('instructor_wallet_movements');
    }
}
