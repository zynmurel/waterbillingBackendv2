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
        Schema::create('readings', function (Blueprint $table) {
            $table->id('reading_id');
            $table->integer('reader_id');
            $table->integer('consumer_id');
            $table->integer('service_period_id');
            $table->integer('previous_reading');
            $table->integer('present_reading');
            $table->text('proof_image')->nullable();
            $table->integer('reading_date');
            $table->unique(['consumer_id','service_period_id']);
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
        Schema::dropIfExists('readings');
    }
};
