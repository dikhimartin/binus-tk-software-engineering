<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id')->nullable();
            $table->uuid('staff_id')->nullable();
            $table->datetime('transaction_date');
            $table->longText('description')->nullable();
            $table->enum('status_transaction', ['pending', 'reject', 'finish']);
            $table->uuid('creator_id')->nullable(true)->index();
            $table->uuid('modifier_id')->nullable(true)->index();
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
        Schema::dropIfExists('transactions');
    }
}
