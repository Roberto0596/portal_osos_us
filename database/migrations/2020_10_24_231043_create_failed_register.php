<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_register', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger('alumn_id')->unsigned()->default(6);
            $table->foreign("alumn_id")->references("id")->on("users")->onDelete('cascade');
            $table->integer('period_id');
            $table->string("message",255);
            $table->integer("status");
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
        Schema::dropIfExists('failed_register');
    }
}
