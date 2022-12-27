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
        Schema::create('case_files', function (Blueprint $table) {
            $table->id();
            $table->String('matter');
            $table->String('type');
            $table->String('file_no');
            $table->boolean('no_conflict_checked');
            $table->foreignId('client_id')->constrained('clients', 'id');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->timestamps();
        });

        Schema::create('user_case_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('case_file_id')->constrained('case_files', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_files');
    }
};
