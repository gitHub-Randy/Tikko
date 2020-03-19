<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTikkosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tikkos')){
            Schema::create('tikkos', function (Blueprint $table) {
                $table->bigIncrements('id')->unsigned();
                $table->bigInteger('user_id')->unsigned();
                $table->string('name');
                $table->string('description');
                $table->decimal('amount', 5, 2);
                $table->string('currency');
                $table->unsignedInteger('account_id');
                $table->date('tikko_date');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('account_id')->references('account_id')->on('bankaccount');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tikkos');
    }
}
