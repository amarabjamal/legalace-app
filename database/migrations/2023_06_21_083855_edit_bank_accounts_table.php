<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            // add opening balance
            $table->after('account_number', function (Blueprint $table) {
                $table->decimal('opening_balance', 13, 4, true)->default(0.0000);
            });
            //Rename created by to created by user id
            $table->renameColumn('created_by', 'created_by_user_id');
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
};
