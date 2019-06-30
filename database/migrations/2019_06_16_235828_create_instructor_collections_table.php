<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('instructor_wallet_id');
            $table->string('status');
            $table->decimal('amount', 8, 2);
            $table->unsignedBigInteger('wallet_movement_id')->nullable();
            $table->text("reject_reason")->nullable();
            $table->timestamps();

            $table->foreign('instructor_wallet_id')->references('id')->on('instructor_wallets');
            $table->foreign('wallet_movement_id')->references('id')->on('instructor_wallet_movements');

        });

        Schema::table('instructor_wallet_movements', function (Blueprint $table) {
            $table->foreign('collection_id')->references('id')->on('instructor_collections');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructor_wallet_movements', function (Blueprint $table) {
            $table->dropForeign('instructor_wallet_movements_collection_id_foreign');
        });

        Schema::dropIfExists('instructor_collections');
    }
}
