<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('first_name_kana', 50);
            $table->string('last_name_kana', 50);
            $table->integer('postal_code');
            $table->string('prefecture', 50);
            $table->string('municipality_county', 255);
            $table->string('address', 255);
            $table->string('building＿name', 255)->nullable();
            $table->date('birthday');
            $table->integer('annual_income');
            $table->string('entry_sheet', 1000);
            $table->string('identification_photo_front', 191)->nullable();
            $table->string('identification_photo_reverse', 191)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
