<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComisionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('config', function (Blueprint $table) {
            $table->float('oxxo_comision')->default(92.39);
            $table->float('spei_comision')->default(14.50);
            $table->float('card_comision')->default(70.89);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn('oxxo_comision');
            $table->dropColumn('spei_comision');
            $table->dropColumn('card_comision');
        });
    }
}
