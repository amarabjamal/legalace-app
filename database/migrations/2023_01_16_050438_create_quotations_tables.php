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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->double('deposit_amount');
            $table->boolean('is_paid');
            $table->date('payment_date');
            $table->foreignId('issued_by')->constrained('users','id');
            $table->foreignId('case_file_id')->constrained('case_files','id');
            $table->foreignId('bank_account_id')->constrained('bank_accounts','id');
            $table->timestamps();
        });

        Schema::create('work_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->double('fee');
            $table->foreignId('quotation_id')->constrained('quotations','id');
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
        Schema::dropIfExists('work_descriptions');
        Schema::dropIfExists('quotations');
    }
};
