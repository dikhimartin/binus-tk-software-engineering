<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_type_id');
            $table->uuid('asset_id')->index()->nullable(true);
            $table->string('name');
            $table->text('description')->nullable(true);
            $table->integer('stock')->nullable(true);
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('status');
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
        Schema::dropIfExists('products');
    }
}


