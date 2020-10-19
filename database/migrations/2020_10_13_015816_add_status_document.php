<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusDocument extends Migration
{
    public function up()
    {
        Schema::table('document', function (Blueprint $table) {
            $table->integer('payment')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('document', function (Blueprint $table) {
            $table->dropColumn('payment');
        });
    }
}
