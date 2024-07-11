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
        Schema::create('bank_account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('account_name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('bank_address');
            $table->string('swift_code');
            $table->foreignId('bank_account_type_id')->constrained('bank_account_types', 'id');
            // $table->foreignId('created_by')->constrained('users', 'id');
            $table->foreignId('created_by_user_id')->constrained('users', 'id');
            $table->foreignId('company_id')->constrained('companies', 'id');
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
        Schema::dropIfExists('bank_accounts');
    }
};
