<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionExtraChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_extra_charges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_id')->index();
            $table->uuid('extra_charge_id')->index();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('sub_price', 10, 2);
            $table->integer('sort')->nullable();
            $table->string('additional', 100)->nullable(true);
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_extra_charges');
    }
}
