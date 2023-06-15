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
        Schema::create('claim_voucher_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignId('claim_voucher_id')->constrained('claim_vouchers', 'id');
            $table->foreignId('pay_from_bank_account_id')->constrained('bank_accounts', 'id');
            $table->text('notes');
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
        Schema::dropIfExists('claim_voucher_approvals');
    }
};
