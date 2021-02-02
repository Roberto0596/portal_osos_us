<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRelationDebit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debit', function (Blueprint $table) {
            // $table->dropForeign(['admin_id']);
            // $table->dropForeign(['debit_type_id']);
            $table->foreign("admin_id")->references("id")->on("admin_users");

            $table->foreign("debit_type_id")->references("id")->on("debit_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
