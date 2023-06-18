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
        Schema::table('work_descriptions', function (Blueprint $table) {
            // add company id index
            $table->after('id', function (Blueprint $table) {
                $table->integer('company_id');
                $table->index('company_id');
            });
            // change fee to decimal
            $table->decimal('fee', 13, 4, true)->change();
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
