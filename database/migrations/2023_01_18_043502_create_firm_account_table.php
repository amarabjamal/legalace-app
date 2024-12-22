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
        Schema::create('firm_account', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('description');
            $table->string('transaction_type');
            $table->string('document_number');
            $table->string('upload');
            $table->double('debit');
            $table->double('credit');
            $table->double('balance')->nullable();
            $table->string('payment_method');
            $table->foreignId('bank_account_id')->constrained('bank_accounts', 'id')->nullable();
            $table->string('reference');
            $table->string('created_by');
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
        Schema::dropIfExists('firm_account');
    }
};
