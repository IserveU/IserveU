<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupOneTimeTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('password_resets');

        Schema::table('users', function (Blueprint  $table) {
            $table->dropColumn('remember_token');
        });

        Schema::create('one_time_tokens', function (Blueprint $table) {
            $table->string('token', 128)->index()->unique();
            $table->timestamp('created_at');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('one_time_tokens');

        Schema::table('users', function (Blueprint  $table) {
            $table->string('remember_token', 256);
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }
}
