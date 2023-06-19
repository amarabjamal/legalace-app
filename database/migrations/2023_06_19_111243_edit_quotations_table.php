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
        Schema::table('quotations', function (Blueprint $table) {
            // add subtotal, tax and total
            $table->after('deposit_amount', function (Blueprint $table) {
                $table->decimal('subtotal', 13, 4, true)->nullable();
                $table->decimal('tax_rate', 13, 4, true)->nullable();
                $table->decimal('tax_amount', 13, 4, true)->nullable();
                $table->decimal('total', 13, 4, true)->nullable();
            });
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
