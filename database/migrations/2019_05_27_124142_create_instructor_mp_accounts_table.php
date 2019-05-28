<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorMpAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_mp_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('instructor_id');
            $table->string("random_id");
            $table->string('access_token')->nullable();
            $table->string('public_key')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('mp_user_id')->nullable();
            $table->string('scope')->nullable();
            $table->datetime('expires_on')->nullable();
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
        Schema::dropIfExists('instructor_mp_accounts');
    }
}
