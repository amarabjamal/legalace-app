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
        Schema::create('claim_vouchers', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('ticket_number');
            $table->date('submission_date')->nullable();
            $table->decimal('amount', 13, 4, true);
            $table->integer('status');
            $table->foreignId('approver_user_id')->constrained('users', 'id');
            $table->foreignId('requester_user_id')->constrained('users', 'id');
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
        Schema::dropIfExists('claim_vouchers');
    }
};
