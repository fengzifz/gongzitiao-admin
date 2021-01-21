<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsernameForReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('receipts', function (Blueprint $table) {
            $table->string('name')->after('salary_id');
            $table->string('year')->after('salary_id');
            $table->string('month')->after('salary_id');
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
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('year');
            $table->dropColumn('month');
        });
    }
}
