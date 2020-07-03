<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebit extends Migration
{
    public function up()
    {
        Schema::create('debit', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("concept");
            $table->float("amount");
            $table->bigInteger("admin_id")->unsigned();

            $table->foreign("admin_id")->references("id")->on("admin_users")->onDelete('cascade');

            $table->integer("id_alumno");
            $table->integer("status")->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('debit');
    }
}
