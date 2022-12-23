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
        Schema::create('billings', function (Blueprint $table) {
            $table->id('billing_id');
            $table->integer('consumer_id');
            $table->integer('service_period_id');
            $table->integer('due_date');
            $table->float('previous_bill');
            $table->float('previous_payment');
            $table->float('penalty');
            $table->float('present_bill');
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
        Schema::dropIfExists('billings');
    }
};
