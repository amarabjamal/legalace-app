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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('id_type_id')->constrained("id_types","id");
            $table->string('id_number', 1000);
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('address', 1000);
            $table->timestamps();
            $table->foreignId('created_by')->constrained("users","id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
