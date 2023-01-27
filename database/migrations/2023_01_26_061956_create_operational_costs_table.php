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
            $table->string('details');
            $table->double('amount');
            $table->boolean('is_recurring');
            $table->string('recurring_period');
            $table->boolean('is_paid');
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
        Schema::dropIfExists('operational_costs');
    }
};
