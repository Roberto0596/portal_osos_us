<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeDocumentType extends Migration
{
    public function up()
    {
        Schema::table('document_type', function (Blueprint $table) {
            $table->integer('type')->default(0);
            $table->float('cost')->nullable();
        });
    }
    /*0 = documento de inscripcion, 1 = documento oficial*/
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_type', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('cost');
        });
    }
}
