<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reservation_id');
            $table->string('status'); 
            $table->datetime('paid_at')->nullable();
            $table->string('payment_method_code');
            $table->unsignedBigInteger('mercadopago_payment_id'); // nullable after new pay methods are developed
            $table->decimal('total_amount', 8, 2);
            $table->decimal('payment_provider_fee', 8, 2)->nullable();
            $table->decimal('financing_costs', 8, 2)->nullable();
            $table->decimal('net_received', 8, 2)->nullable();
            $table->string('currency_code');
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations');
            //$table->foreign('mercadopago_payment_id')->references('id')->on('instructors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_payments');
    }
}
