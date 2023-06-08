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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignId('invoice_payment_id')->constrained('invoice_payments', 'id');
            $table->string('receipt_number');
            $table->text('notes');
            $table->boolean('is_sent')->default(0);
            $table->foreignId('created_by_user_id')->constrained('users', 'id');
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
        Schema::dropIfExists('receipts');
    }
};
