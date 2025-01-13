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
        Schema::table('client_accounts', function (Blueprint $table) {
            // Add the transaction_id column
            $table->string('transaction_id')->nullable()->after('reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_accounts', function (Blueprint $table) {
            // Drop the transaction_id column if the migration is rolled back
            $table->dropColumn('transaction_id');
        });
    }
};
