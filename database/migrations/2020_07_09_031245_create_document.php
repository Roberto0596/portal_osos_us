<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) 
        {
            $table->bigIncrements("id");
            $table->string("name",50);
            $table->string("route",50);
            $table->integer("status")->default(0);
            $table->integer("PeriodoId");
            $table->bigInteger("alumn_id")->unsigned();
            $table->foreign("alumn_id")->references("id")->on("users")->onDelete('cascade');
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
        Schema::dropIfExists('document');
    }
}
