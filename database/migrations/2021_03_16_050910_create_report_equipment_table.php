<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');

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
            $table->time("entry_time");
            $table->time("departure_time");
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
        Schema::dropIfExists('report_equipment');
    }
}
