<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielsInDebitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debit', function (Blueprint $table) {
            $table->string("location", 100)->nullable()->default('');
            $table->string("state")->nullable()->default('');
            $table->string("career", 150)->nullable()->default('');
            $table->timestamp("payment_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('debit', function (Blueprint $table) {
            $table->dropColumn("location");
            $table->dropColumn("state");
            $table->dropColumn("career");
            $table->dropColumn("payment_date");
        });
    }
}
