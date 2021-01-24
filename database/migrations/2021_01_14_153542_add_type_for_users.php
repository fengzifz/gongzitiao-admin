<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeForUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('openid')->nullable()->after('username');
            $table->string('code')->nullable()->after('username');
            $table->string('session_key')->nullable()->after('username');
            $table->tinyInteger('type')->default(2)->after('username');
            $table->string('phone', 11)->nullable()->after('username');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('openid');
            $table->dropColumn('code');
            $table->dropColumn('session_key');
            $table->dropColumn('type');
            $table->dropColumn('phone');
        });
    }
}
