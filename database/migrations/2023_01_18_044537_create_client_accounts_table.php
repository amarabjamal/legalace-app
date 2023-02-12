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
        Schema::create('client_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('description');
            $table->string('transaction_type');
            $table->double('debit');
            $table->double('credit');
            $table->double('balance');
            $table->foreignId('company_id')->constrained('companies', 'id');
            $table->foreignId('bank_account_id')->constrained('bank_accounts', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_accounts');
    }
};
