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
        Schema::create('quotation_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignId('quotation_id')->constrained('quotations', 'id');
            $table->date('date');
            $table->foreignId('client_bank_account_id')->constrained('bank_accounts', 'id');
            $table->integer('payment_method_code');
            $table->string('receipt_filename');
            $table->string('description');
            $table->decimal('amount', 13, 4, true);
            $table->foreignId('created_by_user_id')->constrained('users', 'id');
            $table->boolean('is_synced')->default(0);
            $table->foreignId('client_account_id')->nullable()->constrained('client_accounts', 'id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_payments');
    }
};
