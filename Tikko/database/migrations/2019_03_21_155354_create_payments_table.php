<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigInteger('tikko_id')->unsigned();
            $table->bigInteger('payer_id')->unsigned();
            $table->string('payment_id')->nullable();
            $table->boolean('payed', false);
            $table->timestamps();
            $table->primary(['tikko_id', 'payer_id']);
            $table->foreign('tikko_id')->references('id')->on('tikkos')->onDelete('cascade');
            $table->foreign('payer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
