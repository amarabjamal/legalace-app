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
        Schema::create('disbursement_items', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->date('date');
            $table->string('item');
            $table->string('description')->nullable();
            $table->decimal('amount', 13, 4, true);
            $table->string('receipt')->nullable();
            $table->integer('status');
            $table->integer('record_type');
            $table->integer('fund_type');
            $table->foreignId('case_file_id')->constrained('case_files', 'id');
            $table->timestamps();

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
        Schema::dropIfExists('disbursement_items');
    }
};
