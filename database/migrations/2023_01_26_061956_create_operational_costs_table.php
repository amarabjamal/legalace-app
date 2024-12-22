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
        Schema::create('operational_costs', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('details');
            $table->string('payment_method');
            $table->double('amount');
            $table->boolean('is_recurring');
            $table->string('recurring_period');
            $table->boolean('is_paid');
            $table->foreignId('bank_account_id')->constrained('bank_accounts', 'id');
            $table->string('company_id');
            $table->string('first_payment_date');
            $table->string('no_of_payment');
            $table->string('upload');
            $table->string('document_number');
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
        Schema::dropIfExists('operational_costs');
    }
};
