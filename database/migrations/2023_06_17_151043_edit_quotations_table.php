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
            // add company id index
            $table->after('id', function (Blueprint $table) {
                $table->integer('company_id');
                $table->index('company_id');
            });
            // change deposit to decimal
            $table->decimal('deposit_amount', 13, 4, true)->change();
            // default is paid to false
            $table->boolean('is_paid')->default(0)->change();
            // update foreign key issude_by to created by user id
            $table->renameColumn('issued_by', 'created_by_user_id');
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
