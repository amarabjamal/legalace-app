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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('invoice_number');
            $table->integer('status');
            $table->date('issued_at');
            $table->date('due_at');
            $table->decimal('subtotal', 13, 4, true);
            $table->decimal('tax_rate', 13, 4, true)->nullable();
            $table->decimal('tax_amount', 13, 4, true)->nullable();
            $table->decimal('grand_total', 13, 4, true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users', 'id');
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
        Schema::dropIfExists('invoices');
    }
};
