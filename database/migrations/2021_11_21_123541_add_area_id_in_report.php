<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaIdInReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_equipment', function (Blueprint $table) {
            $table->bigInteger("area_id")->unsigned()->nullable();
            $table->foreign("area_id")->references("id")->on("area")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_equipment', function (Blueprint $table) {
            //
        });
    }
}
