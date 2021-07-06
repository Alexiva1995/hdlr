<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReinvertirCapitalPackageIdUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->bigInteger('reinvertir_capital_package_id')->unsigned()->nullable();
            $table->foreign('reinvertir_capital_package_id')->nullable()->references('id')->on('packages');
            $table->bigInteger('reinvertir_capital_inversion_id')->unsigned()->nullable();
            $table->foreign('reinvertir_capital_inversion_id')->nullable()->references('id')->on('inversions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign(['reinvertir_capital_package_id', 'reinvertir_capital_inversion_id']);
        });
    }
}
