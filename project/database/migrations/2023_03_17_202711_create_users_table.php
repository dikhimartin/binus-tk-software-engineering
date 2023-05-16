<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('role_id')->nullable(true);
            $table->uuid('asset_id')->index()->nullable(true);
            $table->char('username', 20)->nullable(true);
            $table->string('name', 50)->nullable(true);
            $table->string('password', 100)->nullable(true);
            $table->string('email', 100)->nullable(true);
            $table->integer('status');
            $table->uuid('creator_id')->nullable(true)->index();
            $table->uuid('modifier_id')->nullable(true)->index();

            $table->integer('sort')->nullable();
            $table->string('additional', 100)->nullable(true);
            $table->rememberToken();
            $table->timestamps();
            $table->unique('username');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
