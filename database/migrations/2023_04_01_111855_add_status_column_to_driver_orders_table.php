<?php

use App\Models\DriverOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToDriverOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table((new DriverOrder())->getTable(), function (Blueprint $table) {
            $table->string('status')->default('pick-up');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table((new DriverOrder())->getTable(), function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
