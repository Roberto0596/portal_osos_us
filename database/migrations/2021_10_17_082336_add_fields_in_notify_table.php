<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notify', function (Blueprint $table) {
            $table->dropForeign('notify_alumn_id_foreign');
            $table->dropColumn('alumn_id');
            $table->string('target', 100)->nullable();
            $table->integer('id_target')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notify', function (Blueprint $table) {
            $table->dropColumn('target');
            $table->dropColumn('id_target');
        });
    }
}
