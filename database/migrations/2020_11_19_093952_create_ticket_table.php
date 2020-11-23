<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("concept");
            $table->bigInteger('alumn_id')->unsigned();
            $table->foreign("alumn_id")->references("id")->on("users")->onDelete('cascade');
            $table->bigInteger('debit_id')->unsigned();
            $table->foreign("debit_id")->references("id")->on("debit")->onDelete('cascade');
            $table->string("route");
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
        Schema::dropIfExists('ticket');
    }
}
