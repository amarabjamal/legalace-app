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
        Schema::table('quotations', function (Blueprint $table) {
            // add sent at
            $table->after('bank_account_id', function (Blueprint $table) {
                $table->dateTime('sent_at')->nullable();
            });

            $table->dropColumn('is_paid');
            $table->dropColumn('payment_date');
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
