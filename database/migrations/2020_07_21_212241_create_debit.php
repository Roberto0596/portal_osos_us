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
            $table->bigInteger("debit_type_id")->unsigned();
            $table->foreign("debit_type_id")->references("id")->on("debit_type")->onDelete('cascade');
            $table->string("description")->nullable();
            $table->float("amount");
            $table->string("payment_method")->nullable();
            $table->bigInteger("admin_id")->unsigned();

            $table->foreign("admin_id")->references("id")->on("admin_users")->onDelete('cascade');

            $table->integer("id_alumno");
            $table->string("id_order",100)->nullable();
            $table->integer("status")->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('debit');
    }
}
