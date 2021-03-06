<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('slug');

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('postal_code')->nullable();
            $table->string('street_name')->nullable();
            $table->string('street_number')->nullable();
            $table->string('unit_number')->nullable();

            $table->integer('community_id')->unsigned()->nullable();

            $table->string('status')->default('private');

            $table->integer('ethnic_origin_id')->unsigned()->nullable();
            $table->date('date_of_birth')->nullable()->default(null);

            $table->date('address_verified_until')->nullable();
            $table->boolean('identity_verified')->default(0);

            $table->json('preferences')->nullable();
            $table->integer('login_attempts')->default(0);
            $table->datetime('locked_until')->nullable();

            $table->date('agreement_accepted_date')->nullable();

            $table->string('api_token', 256)->unique()->index();

            $table->softDeletes();
            $table->string('remember_token', 256)->unique();
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
        Schema::drop('users');
    }
}
