<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumers', function (Blueprint $table) {
            $table->id('id');
            $table->string('user_key');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->dateTime('birthday');
            $table->integer('phone');
            $table->string('civil_status');
            $table->string('name_of_spouse');
            $table->string('barangay');
            $table->string('purok');
            $table->integer('household_no');
            $table->integer('first_reading');
            $table->string('usage_type');
            $table->integer('serial_no');
            $table->string('brand');
            $table->string('status');
            $table->string('delinquent');
            $table->string('registered_at');
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
        Schema::dropIfExists('consumers');
    }
};
