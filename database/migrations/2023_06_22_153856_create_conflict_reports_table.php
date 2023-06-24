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
        Schema::create('conflict_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignId('case_file_id')->constrained('case_files', 'id');
            $table->foreignId('created_by_user_id')->constrained('users', 'id');
            $table->text('content');
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
        Schema::dropIfExists('conflict_reports');
    }
};
