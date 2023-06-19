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
        Schema::table('case_files', function (Blueprint $table) {
            // add company id index
            $table->after('id', function (Blueprint $table) {
                $table->integer('company_id');
                $table->index('company_id');
            });
            //Rename created by to created by user id
            $table->renameColumn('created_by', 'created_by_user_id');
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
