<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityMembershipTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_membership_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('facility_id')->unsigned();
            $table->bigInteger('membership_type_id')->unsigned();
            $table->integer('price');
            $table->timestamps();

            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('membership_type_id')->references('id')->on('membership_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facility_membership_type');
    }
}
