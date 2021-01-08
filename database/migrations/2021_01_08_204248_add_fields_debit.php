<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsDebit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debit', function (Blueprint $table) {
            $table->string('enrollment',255)->nullable();
            $table->string('alumn_name',255)->nullable();
            $table->string('alumn_last_name',255)->nullable();
            $table->string('alumn_second_last_name',255)->nullable();
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
            $table->dropColumn('enrollment');
            $table->dropColumn('alumn_name');
            $table->dropColumn('alumn_last_name');
            $table->dropColumn('alumn_second_last_name');
        });
    }
}
