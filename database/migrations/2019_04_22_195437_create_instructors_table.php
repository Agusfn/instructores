<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('provider');
            $table->string('provider_id');
            $table->string('phone_number')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('instagram_username')->nullable();
            $table->string("identification_imgs")->nullable();
            $table->string('professional_cert_imgs')->nullable();
            $table->timestamp('documents_sent_at')->nullable();
            $table->boolean('approved');
            $table->timestamp('approved_at')->nullable();
            $table->string('identification_type')->nullable();
            $table->string('identification_number')->nullable();
            $table->integer('level')->nullable();
            $table->boolean('suspended')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructors');
    }
}
