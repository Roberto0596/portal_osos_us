<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_use', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('area_id')->unsigned();
            $table->foreign("area_id")
            ->references("id")
            ->on("area")
            ->onDelete("cascade");

            $table->bigInteger('equipment_id')->unsigned();
            $table->foreign("equipment_id")
            ->references("id")
            ->on("equipment")
            ->onDelete("cascade");

            $table->bigInteger('alumn_id')->unsigned();
            $table->foreign("alumn_id")
            ->references("id")
            ->on("users")
            ->onDelete("cascade");

            $table->string("enrollment");
            $table->time("entry_time");

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
        Schema::dropIfExists('temp_use');
    }
}
