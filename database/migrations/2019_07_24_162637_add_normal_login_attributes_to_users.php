<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNormalLoginAttributesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('provider')->nullable()->change();
            $table->string('provider_id')->nullable()->change();

            $table->timestamp("email_verified_at")->nullable();
            $table->string("password")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn("email_verified_at");
            $table->dropColumn("password");

            $table->string('provider')->nullable(false)->change();
            $table->string('provider_id')->nullable(false)->change();

        });
    }
}
