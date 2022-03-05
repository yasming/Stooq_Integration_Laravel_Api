<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRequestsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_requests_histories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('name');
            $table->string('symbol');
            $table->float('open');
            $table->float('high');
            $table->float('low');
            $table->float('close');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('user_requests_histories');
    }
}
