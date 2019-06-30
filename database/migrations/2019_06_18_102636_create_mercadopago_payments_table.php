<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMercadopagoPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * Fields are nullable because the MP payment resource can fail to be created in case of a request error.
         */
        Schema::create('mercadopago_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reservation_payment_id')->nullable();
            $table->integer('mp_payment_id')->nullable();
            $table->string('status')->nullable(); // MP status or 'error'.
            $table->string('status_detail')->nullable();
            $table->datetime('date_created')->nullable();
            $table->datetime('date_approved')->nullable();
            $table->datetime('date_updated')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->string('payment_type_id')->nullable();
            $table->string('ext_resource_url')->nullable();
            $table->integer('installment_amount')->nullable();
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->decimal('collector_fee', 8, 2)->nullable();
            $table->decimal('financing_costs', 8, 2)->nullable();
            $table->decimal('net_received', 8, 2)->nullable();
            $table->string('last_four_digits')->nullable();
            $table->string('issuer_id')->nullable();
            $table->string('cardholder_name')->nullable();
            $table->string('cardholder_id_type')->nullable();
            $table->string('cardholder_id')->nullable();
            $table->string('error_msg')->nullable();

            $table->foreign('reservation_payment_id')->references('id')->on('reservation_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercadopago_payments');
    }
}
