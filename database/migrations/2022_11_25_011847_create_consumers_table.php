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
            $table->id('consumer_id');
            $table->integer('user_id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('gender', 10);
            $table->integer('birthday');
            $table->string('phone', 15);
            $table->string('civil_status', 15);
            $table->string('name_of_spouse')->nullable();
            $table->integer('brgyprk_id');
            $table->integer('household_no');
            $table->integer('first_reading');
            $table->string('usage_type', 15);
            $table->integer('serial_no');
            $table->string('brand', 20);
            $table->string('status', 20);
            $table->boolean('delinquent');
            $table->string('registered_at', 20);
            $table->unique(['serial_no']);
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
