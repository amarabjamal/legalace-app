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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('case_file_id')->constrained('case_files', 'id')->after('company_id');
            $table->decimal('subtotal', 13, 4, true)->nullable()->change();
            $table->decimal('grand_total', 13, 4, true)->nullable()->change();
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
